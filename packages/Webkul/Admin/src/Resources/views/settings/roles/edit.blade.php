<x-admin::layouts>
    <!-- Page Title -->
    <x-slot:title>
        @lang('admin::app.settings.roles.edit.title')
    </x-slot>

    {!! view_render_event('krayin.admin.settings.roles.edit.before', ['role' => $role]) !!}

    <x-admin::form
        method="PUT"
        :action="route('admin.settings.roles.update', $role->id)"
    >

        {!! view_render_event('krayin.admin.settings.roles.edit.form_controls.before', ['role' => $role]) !!}

        <div class="flex items-center justify-between rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
            <div class="flex flex-col gap-2">
                <div class="flex cursor-pointer items-center">
                    <x-admin::breadcrumbs 
                        name="settings.roles.edit"
                        :entity="$role"
                    />
                </div>

                <div class="text-xl font-bold dark:text-white">
                    @lang('admin::app.settings.roles.edit.title')
                </div>
            </div>

            <div class="flex items-center gap-x-2.5">
                <!-- Save button for roles -->
                <div class="flex items-center gap-x-2.5">
                    <button
                        type="submit"
                        class="primary-button"
                    >
                        @lang('admin::app.settings.roles.edit.save-btn')
                    </button>
                </div>
            </div>
        </div>

        <!-- body content -->
        <div class="mt-3.5 flex gap-2.5 max-xl:flex-wrap">
            <!-- Left sub-component -->
            <div class="flex flex-1 flex-col gap-2 max-xl:flex-auto">

                {!! view_render_event('krayin.admin.settings.roles.edit.card.access_control.before', ['role' => $role]) !!}

                <!-- Access Control Input Fields -->
                <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                    <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                        @lang('admin::app.settings.roles.edit.access-control')
                    </p>

                    <!-- Edit Role for  -->
                    <v-access-control>
                        <!-- Shimmer Effect -->
                        <div class="mb-4">
                            <div class="shimmer mb-1.5 h-4 w-24"></div>

                            <div class="custom-select h-11 w-full rounded-md border bg-white px-3 py-2.5 text-sm font-normal text-gray-600 transition-all hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400"></div>
                        </div>

                        <!-- Roles Checkbox -->
                        <x-admin::shimmer.tree />
                    </v-access-control>
                </div>

                {!! view_render_event('krayin.admin.settings.roles.edit.card.access_control.after', ['role' => $role]) !!}
            </div>

            <!-- Right sub-component -->
            <div class="flex w-[360px] max-w-full flex-col gap-2 max-sm:w-full">
                {!! view_render_event('krayin.admin.settings.roles.edit.card.accordion.general.before', ['role' => $role]) !!}

                <x-admin::accordion>
                    <x-slot:header>
                        <div class="flex items-center justify-between">
                            <p class="p-2.5 text-base font-semibold text-gray-800 dark:text-white">
                                @lang('admin::app.settings.roles.edit.general')
                            </p>
                        </div>
                    </x-slot>

                    <x-slot:content>
                        <!-- Name -->
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.settings.roles.edit.name')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                id="name"
                                name="name"
                                rules="required"
                                value="{{ old('name') ?: $role->name }}"
                                :label="trans('admin::app.settings.roles.edit.name')"
                                :placeholder="trans('admin::app.settings.roles.edit.name')"
                            />

                            <x-admin::form.control-group.error control-name="name" />
                        </x-admin::form.control-group>

                        <!-- Description -->
                        <x-admin::form.control-group class="!mb-0">
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.settings.roles.edit.description')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="textarea"
                                id="description"
                                name="description"
                                rules="required"
                                value="{{ old('description') ?: $role->description }}"
                                :label="trans('admin::app.settings.roles.edit.description')"
                                :placeholder="trans('admin::app.settings.roles.edit.description')"
                            />

                            <x-admin::form.control-group.error control-name="description" />
                        </x-admin::form.control-group>
                    </x-slot>
                </x-admin::accordion>

                {!! view_render_event('krayin.admin.settings.roles.edit.card.accordion.general.after', ['role' => $role]) !!}
            </div>
        </div>

        {!! view_render_event('krayin..admin.settings.roles.edit.form_controls.after', ['role' => $role]) !!}

    </x-admin::form>

    {!! view_render_event('krayin.admin.settings.roles.edit.after', ['role' => $role]) !!}

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-access-control-template"
        >
            <div>
                <!-- Permission Type -->
                <x-admin::form.control-group>
                    <x-admin::form.control-group.label class="required">
                        @lang('admin::app.settings.roles.edit.permissions')
                    </x-admin::form.control-group.label>

                    <x-admin::form.control-group.control
                        type="select"
                        id="permission_type"
                        name="permission_type"
                        v-model="permission_type"
                        :label="trans('admin::app.settings.roles.edit.permissions')"
                        :placeholder="trans('admin::app.settings.roles.edit.permissions')"
                    >
                        <option value="custom">
                            @lang('admin::app.settings.roles.edit.custom')
                        </option>

                        <option value="all">
                            @lang('admin::app.settings.roles.edit.all')
                        </option>
                    </x-admin::form.control-group.control>

                    <x-admin::form.control-group.error control-name="permission_type" />
                </x-admin::form.control-group>
                
                <!-- Tree structure -->
                <div v-if="permission_type == 'custom'">
                    <x-admin::tree.view
                        input-type="checkbox"
                        value-field="key"
                        id-field="key"
                        :items="json_encode(acl()->getItems())"
                        :value="json_encode($role->permissions ?? [])"
                        :fallback-locale="config('app.fallback_locale')"
                    />
                </div>
            </div>
        </script>

        <script type="module">
            app.component('v-access-control', {
                template: '#v-access-control-template',

                data() {
                    return {
                        permission_type: "{{ $role->permission_type }}"
                    };
                }
            })
        </script>
    @endPushOnce
</x-admin::layouts>
