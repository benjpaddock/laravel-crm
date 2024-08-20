
<v-lead-products></v-lead-products>

@pushOnce('scripts')
    <script 
        type="text/x-template" 
        id="v-lead-products-template"
    >
        <div v-if="products.length" class="flex flex-col gap-4 p-3">
             <!-- Table -->
             <x-admin::table class="w-full table-fixed">
                <!-- Table Head -->
                <x-admin::table.thead class="rounded-lg border border-gray-200 px-4 py-2 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
                    <x-admin::table.thead.tr>
                        <x-admin::table.th>
                            @lang('admin::app.leads.view.products.product-name')
                        </x-admin::table.th>
            
                        <x-admin::table.th class="text-right">
                            @lang('admin::app.leads.view.products.quantity')
                        </x-admin::table.th>
            
                        <x-admin::table.th class="text-right">
                            @lang('admin::app.leads.view.products.price')
                        </x-admin::table.th>
            
                        <x-admin::table.th class="text-right">
                            @lang('admin::app.leads.view.products.amount')
                        </x-admin::table.th>

                        <x-admin::table.th class="text-right">
                            @lang('admin::app.leads.view.products.action')
                        </x-admin::table.th>
                    </x-admin::table.thead.tr>
                </x-admin::table.thead>

                <!-- Table Body -->
                <x-admin::table.tbody class="rounded-lg border border-gray-200 bg-gray-500 px-4 py-2 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
                    
                    <!-- Product Item Vue Component -->
                    <v-product-item
                        v-for='(product, index) in products'
                        :product="product"
                        :key="index"
                        :index="index"
                        @onRemoveProduct="removeProduct($event)"
                    ></v-product-item>
                </x-admin::table.tbody>
            </x-admin::table>

            <!-- Add New Product Item -->
            <span
                class="cursor-pointer text-brandColor"
                @click="addProduct"
            >
                + @lang('admin::app.leads.view.products.add-more')
            </span>
        </div>

        <div v-else>
            <div class="grid justify-center justify-items-center gap-3.5 py-12">
                <img src="{{ admin_vite()->asset('images/empty-placeholders/products.svg') }}">
                
                <div class="flex flex-col items-center gap-2">
                    <p class="text-xl font-semibold dark:text-white">
                        @lang('admin::app.leads.view.products.empty-title')
                    </p>
                    
                    <p class="text-gray-400 dark:text-gray-400">
                        @lang('admin::app.leads.view.products.empty-info')
                    </p>
                </div>

                <div
                    class="secondary-button"
                    @click="addProduct"
                >
                     @lang('admin::app.leads.view.products.add-product')
                </div>
            </div>
        </div>
    </script>

    <script 
        type="text/x-template" 
        id="v-product-item-template"
    >
        <x-admin::table.thead.tr class="border-b">
            <!-- Product Name -->
            <x-admin::table.td>
                <x-admin::form.control-group>
                    <x-admin::lookup 
                        ::src="src"
                        ::name="`${inputName}[name]`"
                        ::params="params"
                        :placeholder="trans('admin::app.leads.view.products.product-name')"
                        @on-selected="(product) => addProduct(product)"
                        ::value="{ id: product.product_id, name: product.name }"
                    />

                    <x-admin::form.control-group.control
                        type="hidden"
                        ::name="`${inputName}[product_id]`"
                        v-model="product.product_id"
                        rules="required"
                        :label="trans('admin::app.leads.view.products.product-name')"
                        :placeholder="trans('admin::app.leads.view.products.product-name')"
                        ::url="url(product)"
                    />
            
                    <x-admin::form.control-group.error ::name="`${inputName}[product_id]`" />
                </x-admin::form.control-group>
            </x-admin::table.td>
            
            <!-- Product Quantity -->
            <x-admin::table.td class="text-right">
                <x-admin::form.control-group>
                    <x-admin::form.control-group.control
                        type="inline"
                        ::name="`${inputName}[quantity]`"
                        ::value="product.quantity"
                        rules="required|decimal:4"
                        :label="trans('admin::app.leads.view.products.quantity')"
                        :placeholder="trans('admin::app.leads.view.products.quantity')"
                        @on-change="(event) => product.quantity = event.value"
                        ::url="url(product)"
                    />
                </x-admin::form.control-group>
            </x-admin::table.td>
        
            <!-- Price -->
            <x-admin::table.td class="text-right">
                <x-admin::form.control-group>
                <x-admin::form.control-group.control
                        type="inline"
                        ::name="`${inputName}[price]`"
                        ::value="product.price"
                        rules="required|decimal:4"
                        :label="trans('admin::app.leads.view.products.price')"
                        :placeholder="trans('admin::app.leads.view.products.price')"
                        @on-change="(event) => product.price = event.value"
                        ::url="url(product)"
                    />
                </x-admin::form.control-group>
            </x-admin::table.td>
        
            <!-- Total -->
            <x-admin::table.td class="text-right">
                <x-admin::form.control-group>
                    <x-admin::form.control-group.control
                        type="inline"
                        ::name="`${inputName}[amount]`"
                        ::value="product.price * product.quantity"
                        rules="required|decimal:4"
                        :label="trans('admin::app.leads.view.products.total')"
                        :placeholder="trans('admin::app.leads.view.products.total')"
                        ::allowEdit="false"
                        ::url="url(product)"
                    />
                </x-admin::form.control-group>
            </x-admin::table.td>

            <!-- Action -->
            <x-admin::table.td class="text-right">
                <x-admin::form.control-group >
                    <i  
                        @click="removeProduct"
                        class="icon-delete cursor-pointer text-2xl"
                    ></i>
                </x-admin::form.control-group>
            </x-admin::table.td>
        </x-admin::table.thead.tr>
    </script>

    <script type="module">
        app.component('v-lead-products', {
            template: '#v-lead-products-template',

            props: ['data'],

            data: function () {
                return {
                    products: @json($lead->products),
                }
            },

            methods: {
                addProduct() {
                    this.products.push({
                        id: null,
                        product_id: null,
                        name: '',
                        quantity: 0,
                        price: 0,
                        amount: null,
                    })
                },
                
                removeProduct (product) {
                    const index = this.products.indexOf(product);
                    this.products.splice(index, 1);
                },
            },
        });

        app.component('v-product-item', {
            template: '#v-product-item-template',

            props: ['index', 'product'],

            data() {
                return {
                    products: [],
                }
            },

            computed: {
                inputName() {
                    if (this.product.id) {
                        return "products[" + this.product.id + "]";
                    }

                    return "products[product_" + this.index + "]";
                },
                
                src() {
                    return '{{ route('admin.products.search') }}';
                },

                params() {
                    return {
                        params: {
                            query: this.product.name
                        },
                    };
                },
            },

            methods: {
                /**
                 * Add the product.
                 * 
                 * @param {Object} result
                 * 
                 * @return {void}
                 */
                addProduct(result) {
                    this.product.product_id = result.id;

                    this.product.name = result.name;
                    
                    this.product.price = result.price;
                    
                    this.product.quantity = result.quantity ?? 0;
                },
                  
                /**
                 * Remove the product.
                 * 
                 * @return {void}
                 */
                removeProduct () {
                    this.$emit('onRemoveProduct', this.product)
                },
                
                url(product) {
                    return '{{ route('admin.leads.update', $lead->id) }}';
                }
            }
        });
    </script>
@endPushOnce