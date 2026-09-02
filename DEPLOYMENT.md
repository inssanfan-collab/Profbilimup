# Перенос BilimUP на боевой VPS

Чек-лист для переноса приложения с локальной Docker-разработки на продакшн-сервер. Ничего из этого не выполнялось автоматически — выполняется вручную, когда будет готов доступ к VPS и домену.

## 1. Требования к серверу

- Ubuntu 22.04/24.04 LTS (или аналог)
- Минимум 2 vCPU / 4 ГБ RAM (растёт с числом одновременных слушателей и генерацией PDF)
- Домен, направленный A-записью на IP сервера
- Открытые порты 80/443 (HTTP/HTTPS), 22 (SSH)

## 2. Компоненты стека

| Компонент | Версия | Примечание |
|---|---|---|
| PHP | 8.3+ | с расширениями: pgsql, redis, gd, zip, mbstring, curl, bcmath |
| PostgreSQL | 16+ | отдельный управляемый сервис или локально на VPS |
| Nginx | последняя стабильная | реверс-прокси перед PHP-FPM |
| Redis | 7+ | кэш, очереди, сессии |
| Supervisor | — | держит воркер очереди (`queue:work`) живым |
| Node.js | 20+ | только на этапе сборки фронтенда (`npm run build`), не нужен в рантайме |
| Composer | 2.x | установка PHP-зависимостей |

Можно развернуть либо через Docker Compose (переиспользуя `compose.yaml` как основу, убрав dev-специфичные вещи вроде Xdebug и bind-mount исходников), либо классической связкой Nginx + PHP-FPM напрямую на сервере. Ниже — чек-лист для классического варианта; шаги 4–9 применимы к обоим.

## 3. Подготовка сервера

```bash
sudo apt update && sudo apt upgrade -y
sudo apt install -y nginx postgresql-client redis-server supervisor git unzip
# PHP 8.3 + расширения — через ppa:ondrej/php или системный пакет, в зависимости от дистрибутива
```

## 4. Код и зависимости

```bash
git clone <repo> /var/www/bilimup
cd /var/www/bilimup
composer install --no-dev --optimize-autoloader
npm ci && npm run build
```

## 5. Переменные окружения (`.env`)

Скопировать `.env.example` в `.env` и заполнить **реальными продакшн-значениями**:

- `APP_ENV=production`, `APP_DEBUG=false`
- `APP_URL=https://<домен>`
- Новый `APP_KEY` (`php artisan key:generate`) — не переиспользовать локальный
- `DB_*` — реквизиты продакшн-базы PostgreSQL
- `REDIS_*` — если Redis не на localhost
- `ADMIN_EMAIL` / `ADMIN_PASSWORD` — для первичного сидирования; **сменить пароль в интерфейсе сразу после первого входа**
- `BBB_URL` / `BBB_SECRET` — те же боевые креды vc.nitc.kz, что и сейчас в `.env` локально. **Никогда не коммитить эти значения в git** — переносить вручную (например, через `scp` файла `.env` или секрет-менеджер), не через репозиторий.
- `MAIL_*` — если в будущем потребуется email (сейчас не используется, уведомления только внутри приложения)
- `QUEUE_CONNECTION=database` (или `redis`, если настроен)

## 6. База данных и хранилище

```bash
php artisan migrate --force
php artisan db:seed --force   # только на самом первом деплое — создаёт админа
php artisan storage:link
```

- Настроить регулярный `pg_dump` бэкап базы (cron, например ежедневно) с ротацией и хранением вне сервера.
- Каталог `storage/app/public` (сертификаты, файлы уроков, лого) должен быть на постоянном томе — при масштабировании на несколько серверов вынести на S3-совместимое хранилище (`FILESYSTEM_DISK`).

## 7. Очереди и планировщик

`supervisor` конфиг (`/etc/supervisor/conf.d/bilimup-worker.conf`):

```ini
[program:bilimup-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/bilimup/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
numprocs=2
user=www-data
stopwaitsecs=3600
```

```bash
sudo supervisorctl reread && sudo supervisorctl update && sudo supervisorctl start bilimup-worker:*
```

Генерация PDF-сертификатов и запросы к серверу видеосвязи идут через очередь — без воркера они просто не будут обрабатываться.

## 8. Nginx + HTTPS

- Стандартный конфиг Laravel (root на `public/`, `try_files` на `index.php`).
- HTTPS через Let's Encrypt (`certbot --nginx -d <домен>`).
- Редирект с `http://` на `https://`.

## 9. Финальные шаги и проверка

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Проверить вручную по чек-листу:
- [ ] Вход администратором, смена пароля с дефолтного
- [ ] Создание курса → урока → теста, публикация
- [ ] Приглашение слушателя, установка пароля по ссылке
- [ ] Полное прохождение курса → генерация сертификата (PDF открывается, QR ведёт на `/certificates/verify/...` по боевому домену)
- [ ] Планирование видеоурока → реальное подключение к vc.nitc.kz с боевого домена
- [ ] Переключение языка RU/KK
- [ ] Очередь (`supervisorctl status`) в состоянии RUNNING

## 10. После первого запуска

- Сменить пароль дефолтного администратора.
- Заполнить «Настройки организации» (название на RU/KK, логотип, ФИО руководителя) — это сразу отражается на новых сертификатах.
- Убедиться, что `.env` не попал в git (`git status` в `/var/www/bilimup` не должен показывать `.env`).
