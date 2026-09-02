<div class="py-12">
    <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-4">
        <div class="flex items-center justify-end">
            <a href="{{ route('admin.curators.create') }}" wire:navigate
                class="inline-flex items-center px-4 py-2.5 bg-blue-700 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-blue-800">
                {{ __('Добавить куратора') }}
            </a>
        </div>

        <div class="bg-white overflow-hidden rounded-xl border border-gray-100 shadow-card">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('ФИО') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Права') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Курсы') }}</th>
                        <th class="px-6 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($curators as $curator)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $curator->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $curator->email }}</td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-1">
                                    @forelse ($curator->permissions ?? [] as $permission)
                                        <x-badge color="blue">{{ \App\Enums\CuratorPermission::from($permission)->label() }}</x-badge>
                                    @empty
                                        <span class="text-gray-400">—</span>
                                    @endforelse
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $curator->has_all_courses_access ? __('Все курсы') : __(':count курсов', ['count' => $curator->curator_courses_count]) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <a href="{{ route('admin.curators.edit', $curator) }}" wire:navigate class="text-blue-700 hover:text-blue-900">{{ __('Редактировать') }}</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <x-empty-state icon="users">{{ __('Кураторов пока нет.') }}</x-empty-state>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
