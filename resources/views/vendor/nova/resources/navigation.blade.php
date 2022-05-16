@if (count(\Laravel\Nova\Nova::resourcesForNavigation(request())))
    <h3 class="flex items-center font-normal text-white mb-6 text-base no-underline">
        <svg class="sidebar-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
            <path fill="var(--sidebar-icon)"
                d="M3 1h4c1.1045695 0 2 .8954305 2 2v4c0 1.1045695-.8954305 2-2 2H3c-1.1045695 0-2-.8954305-2-2V3c0-1.1045695.8954305-2 2-2zm0 2v4h4V3H3zm10-2h4c1.1045695 0 2 .8954305 2 2v4c0 1.1045695-.8954305 2-2 2h-4c-1.1045695 0-2-.8954305-2-2V3c0-1.1045695.8954305-2 2-2zm0 2v4h4V3h-4zM3 11h4c1.1045695 0 2 .8954305 2 2v4c0 1.1045695-.8954305 2-2 2H3c-1.1045695 0-2-.8954305-2-2v-4c0-1.1045695.8954305-2 2-2zm0 2v4h4v-4H3zm10-2h4c1.1045695 0 2 .8954305 2 2v4c0 1.1045695-.8954305 2-2 2h-4c-1.1045695 0-2-.8954305-2-2v-4c0-1.1045695.8954305-2 2-2zm0 2v4h4v-4h-4z" />
        </svg>
        <span class="sidebar-label">{{ __('Resources') }}</span>
    </h3>

    @foreach ($navigation as $group => $resources)
        @if (count($groups) > 1)
            <div class="flex items-center w-full p-2 text-base font-normal text-white rounded-lg group cursor-pointer"
                aria-controls="dropdown-{{ $group }}" data-collapse-toggle="dropdown-{{ $group }}"
                onclick="toggleMenu('dropdown-{{ $group }}')">
                <span class="flex-1 ml-3 text-left whitespace-nowrap font-semibold"
                    sidebar-toggle-item>{{ $group }}</span>
                <svg sidebar-toggle-item class="w-6 h-6 text-white mt-2" fill="currentColor" viewBox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                        clip-rule="evenodd"></path>
                </svg>
            </div>
        @endif

        <ul id="dropdown-{{ $group }}" class="hidden py-2 space-y-3">
            @foreach ($resources as $resource)
                <li class="leading-tight ml-8 text-sm">
                    <router-link
                        :to="{
                            name: 'index',
                            params: {
                                resourceName: '{{ $resource::uriKey() }}'
                            }
                        }"
                        class="text-white text-justify no-underline dim"
                        dusk="{{ $resource::uriKey() }}-resource-link">
                        {{ $resource::label() }}
                    </router-link>
                </li>
            @endforeach
        </ul>
    @endforeach
@endif
<script>
    function toggleMenu(group) {
        let groups = document.querySelectorAll('ul[id]')
        for (item of groups) {
            if (!item.classList.contains('hidden')) {
                item.classList.add('hidden');
                continue;
            }
            item.classList.toggle('hidden', item.id != group);
        }

    }
</script>
