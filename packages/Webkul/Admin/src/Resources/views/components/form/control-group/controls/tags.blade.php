<v-control-tags
    :errors="errors"
    {{ $attributes }}
></v-control-tags>

@pushOnce('scripts')
    <script type="text/x-template" id="v-control-tags-template">
        <div class="flex min-h-[38px] w-full items-center rounded border border-gray-200 px-2.5 py-1.5 text-sm font-normal text-gray-800 transition-all hover:border-gray-400">
            <ul class="flex flex-wrap items-center gap-1">
                <li
                    class="flex items-center gap-1 rounded-md bg-slate-100 pl-2"
                    v-for="(tag, index) in tags"
                >
                    <x-admin::form.control-group.control
                        type="hidden"
                        ::name="name + '[' + index + ']'"
                        ::value="tag"
                    />

                    @{{ tag }}

                    <span
                        class="icon-cross-large cursor-pointer p-0.5 text-xl"
                        @click="removeTag(tag)"
                    ></span>
                </li>

                <li>
                    <v-field
                        v-slot="{ field, errors }"
                        :name="'temp-' + name"
                        v-model="input"
                        :rules="inputRules"
                        :label="label"
                    >
                        <input
                            type="text"
                            :name="'temp-' + name"
                            v-bind="field"
                            :placeholder="placeholder"
                            :label="label"
                            @keydown.enter.prevent="addTag"
                        />
                    </v-field>

                    <template v-if="! tags.length && input != ''">
                        <v-field
                            v-slot="{ field, errors }"
                            :name="name + '[' + 0 +']'"
                            :value="input"
                            :rules="rules"
                            :label="label"
                        >
                            <input
                                type="hidden"
                                :name="name + '[0]'"
                                v-bind="field"
                            />
                        </v-field>
                    </template>
                </li>
            </ul>
        </div>

        <v-error-message
            :name="'temp-' + name"
            v-slot="{ message }"
        >
            <p
                class="mt-1 text-xs italic text-red-600"
                v-text="message"
            >
            </p>
        </v-error-message>
    </script>

    <script type="module">
        app.component('v-control-tags', {
            template: '#v-control-tags-template',

            props: [
                'name',
                'label',
                'placeholder',
                'rules',
                'inputRules',
                'data',
                'errors',
            ],

            data: function () {
                return {
                    tags: this.data ? this.data : [],

                    input: '',
                }
            },

            mounted() {
                console.log(this.data);
                
            },

            methods: {
                addTag: function() {
                    if (this.errors['temp-' + this.name]) {
                        return;
                    }

                    this.tags.push(this.input.trim());
                    
                    this.$emit('tags-updated', this.tags);

                    this.input = '';
                },

                removeTag: function(tag) {
                    this.tags = this.tags.filter(function (tempTag) {
                        return tempTag != tag;
                    });

                    this.$emit('tags-updated', this.tags);
                },
            }
        });
    </script>
@endpushOnce
