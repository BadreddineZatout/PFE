@if (count(\Laravel\Nova\Nova::resourcesForNavigation(request())))
    <div class="sticky top-10">
        @foreach ($navigation as $group => $resources)
            @if (count($groups) > 1)
                <div class="flex justify-between items-center w-full py-2 text-base font-normal text-white rounded-lg group cursor-pointer"
                    aria-controls="dropdown-{{ $group }}" data-collapse-toggle="dropdown-{{ $group }}"
                    onclick="toggleMenu('dropdown-{{ $group }}')">
                    <div class="flex gap-2">
                        @php
                            $icon = 'icons.' . strtolower($group);
                        @endphp
                        @include($icon)
                        <span class="flex-1 text-left whitespace-nowrap" sidebar-toggle-item>{{ $group }}</span>
                    </div>
                    <div>
                        <svg sidebar-toggle-item class="w-6 h-6 text-white mt-2" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </div>

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
    </div>
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
