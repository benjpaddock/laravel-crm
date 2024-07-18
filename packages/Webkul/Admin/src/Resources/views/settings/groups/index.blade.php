<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.settings.groups.index.title')
    </x-slot>

    <v-group-settings>
        <div class="flex items-center justify-between rounded-lg border border-gray-200 bg-white py-2 pl-2 pr-4 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
            <div class="flex flex-col">
                <div class="flex cursor-pointer items-center">
                    <i class="icon-left-arrow text-2xl text-gray-800"></i>
    
                    <a
                        href="{{ route('admin.settings.groups.index') }}"
                        class="text-xs text-gray-800 dark:text-gray-300"
                    >
                        Settings
                    </a>
                </div>
    
                <div class="pl-3 text-xl font-normal dark:text-gray-300">
                    @lang('admin::app.settings.groups.index.title')
                </div>
            </div>
    
            <div class="flex items-center gap-x-2.5">
                <!-- Create button for person -->
                <div class="flex items-center gap-x-2.5">
                    <a
                        href="{{ route('admin.settings.groups.create') }}"
                        class="primary-button"
                    >
                        @lang('admin::app.settings.groups.index.create-btn')
                    </a>
                </div>
            </div>
        </div>
    
        <!-- DataGrid Shimmer -->
        <x-admin::shimmer.datagrid />
    </v-group-settings>

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="group-settings-template"
        >
            <div class="flex items-center justify-between rounded-lg border border-gray-200 bg-white py-2 pl-2 pr-4 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
                <div class="flex flex-col">
                    <div class="flex cursor-pointer items-center">
                        <i class="icon-left-arrow text-2xl text-gray-800"></i>
        
                        <a
                            href="{{ route('admin.settings.groups.index') }}"
                            class="text-xs text-gray-800 dark:text-gray-300"
                        >
                            Settings
                        </a>
                    </div>
        
                    <div class="pl-3 text-xl font-normal dark:text-gray-300">
                        @lang('admin::app.settings.groups.index.title')
                    </div>
                </div>
        
                <div class="flex items-center gap-x-2.5">
                    <!-- Create button for person -->
                    <div class="flex items-center gap-x-2.5">
                        {!! view_render_event('krayin.admin.settings.groups.index.create-button.before') !!}
        
                        <x-admin::button
                            button-type="button"
                            class="primary-button justify-center"
                            :title="trans('admin::app.settings.groups.index.create-btn')"
                            @click="selectedGroup=false; $refs.groupUpdateAndCreateModal.toggle()"
                        />
        
                        {!! view_render_event('krayin.admin.settings.groups.index.create-button.after') !!}
                    </div>
                </div>
            </div>
        
            {!! view_render_event('krayin.admin.settings.groups.index.datagrid.before') !!}
        
            <x-admin::datagrid
                src="{{ route('admin.settings.groups.index') }}"
                ref="datagrid"
            >
                <template #body="{
                    isLoading,
                    available,
                    applied,
                    selectAll,
                    sort,
                    performAction
                }">
                    <template v-if="isLoading">
                        <x-admin::shimmer.datagrid.table.body />
                    </template>
        
                    <template v-else>
                        <div
                            v-for="record in available.records"
                            class="row grid items-center gap-2.5 border-b px-4 py-4 text-gray-600 transition-all hover:bg-gray-50 dark:border-gray-800 dark:text-gray-300 dark:hover:bg-gray-950"
                            :style="`grid-template-columns: repeat(${gridsCount}, minmax(0, 1fr))`"
                        >
                            <!-- Currency ID -->
                            <p>@{{ record.id }}</p>
        
                            <!-- Currency Name -->
                            <p>@{{ record.name }}</p>
        
                            <!-- Currency Code -->
                            <p>@{{ record.description }}</p>
        
                            <!-- Actions -->
                            <div class="flex justify-end">
                                <a @click="selectedGroup=true; editModal(record.actions.find(action => action.index === 'edit')?.url)">
                                    <span
                                        :class="record.actions.find(action => action.index === 'edit')?.icon"
                                        class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"
                                    >
                                    </span>
                                </a>
    
                                <a @click="performAction(record.actions.find(action => action.index === 'delete'))">
                                    <span
                                        :class="record.actions.find(action => action.index === 'delete')?.icon"
                                        class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"
                                    >
                                    </span>
                                </a>
                            </div>
                        </div>
                    </template>
                </template>
            </x-admin::datagrid>

            <x-admin::form
                v-slot="{ meta, errors, handleSubmit }"
                as="div"
                ref="modalForm"
            >
                <form @submit="handleSubmit($event, updateOrCreate)">
                    {!! view_render_event('krayin.admin.settings.groups.create_form_controls.before') !!}

                    <x-admin::modal ref="groupUpdateAndCreateModal">
                        <!-- Modal Header -->
                        <x-slot:header>
                            <p class="text-lg font-bold text-gray-800 dark:text-white">
                                @{{ 
                                    selectedGroup
                                    ? "@lang('admin::app.settings.groups.index.edit.title')" 
                                    : "@lang('admin::app.settings.groups.index.create.title')"
                                }}
                            </p>
                        </x-slot>

                        <!-- Modal Content -->
                        <x-slot:content>
                            {!! view_render_event('krayin.admin.settings.groups.create.before') !!}

                            <x-admin::form.control-group.control
                                type="hidden"
                                name="id"
                            />

                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.settings.groups.index.create.name')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="text"
                                    id="name"
                                    name="name"
                                    rules="required"
                                    :label="trans('admin::app.settings.groups.index.create.name')"
                                    :placeholder="trans('admin::app.settings.groups.index.create.name')"
                                />

                                <x-admin::form.control-group.error control-name="name" />
                            </x-admin::form.control-group>

                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.settings.groups.index.create.description')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="textarea"
                                    id="description"
                                    name="description"
                                    rules="required"
                                    :label="trans('admin::app.settings.groups.index.create.description')"
                                    :placeholder="trans('admin::app.settings.groups.index.create.description')"
                                />

                                <x-admin::form.control-group.error control-name="description" />
                            </x-admin::form.control-group>

                            {!! view_render_event('krayin.admin.settings.groups.create.after') !!}
                        </x-slot>

                        <!-- Modal Footer -->
                        <x-slot:footer>
                            <x-admin::button
                                button-type="submit"
                                class="primary-button justify-center"
                                :title="trans('admin::app.settings.groups.index.create.save-btn')"
                                ::loading="isProcessing"
                                ::disabled="isProcessing"
                            />
                        </x-slot>
                    </x-admin::modal>

                    {!! view_render_event('krayin.admin.settings.groups.create_form_controls.after') !!}
                </form>
            </x-admin::form>

            {!! view_render_event('krayin.admin.settings.groups.index.datagrid.after') !!}
        </script>

        <script type="module">
            app.component('v-group-settings', {
                template: '#group-settings-template',
        
                data() {
                    return {
                        isProcessing: false,
                    };
                },
        
                computed: {
                    gridsCount() {
                        let count = this.$refs.datagrid.available.columns.length;

                        if (this.$refs.datagrid.available.actions.length) {
                            ++count;
                        }

                        if (this.$refs.datagrid.available.massActions.length) {
                            ++count;
                        }

                        return count;
                    },
                },

                methods: {
                    updateOrCreate(params, {resetForm, setErrors}) {
                        this.isProcessing = true;

                        this.$axios.post(params.id ? `{{ route('admin.settings.groups.update', '') }}/${params.id}` : "{{ route('admin.settings.groups.store') }}", {
                            ...params,
                            _method: params.id ? 'put' : 'post'
                        }, {
                            headers: {
                                'Content-Type': 'multipart/form-data',
                            }
                        }).then(response => {
                            this.isProcessing = false;

                            this.$refs.groupUpdateAndCreateModal.toggle();

                            this.$refs.datagrid.get();

                            resetForm();
                        }).catch(error => {
                            this.isProcessing = false;

                            if (error.response.status === 422) {
                                setErrors(error.response.data.errors);
                            }
                        });
                    },

                    editModal(url) {
                        this.$axios.get(url)
                            .then(response => {
                                this.$refs.modalForm.setValues(response.data.data);
                                
                                this.$refs.groupUpdateAndCreateModal.toggle();
                            })
                            .catch(error => {});
                    },
                },
            });
        </script>
    @endPushOnce
</x-admin::layouts>
