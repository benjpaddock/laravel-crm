@php
    $quote = app('\Webkul\Quote\Repositories\QuoteRepository')->getModel();

    if (isset($lead)) {
        $quote->fill([
            'person_id'       => $lead->person_id,
            'user_id'         => $lead->user_id,
            'billing_address' => $lead->person->organization ? $lead->person->organization->address : null
        ]);
    }
@endphp

<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.quotes.create.title')
    </x-slot>

    <x-admin::form :action="route('admin.quotes.store')">
        <div class="flex items-center justify-between rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
            <div class="flex flex-col gap-2">
                <div class="flex cursor-pointer items-center">
                    <x-admin::breadcrumbs 
                        name="quotes.create" 
                    />
                </div>

                <div class="text-xl font-bold dark:text-white">
                    @lang('admin::app.quotes.create.title')
                </div>
            </div>

            <div class="flex items-center gap-x-2.5">
                <!-- Save button for person -->
                <div class="flex items-center gap-x-2.5">
                    <button
                        type="submit"
                        class="primary-button"
                    >
                        @lang('admin::app.quotes.create.save-btn')
                    </button>
                </div>
            </div>
        </div>

        <v-quote :errors="errors"></v-quote>
    </x-admin::form>

    @pushOnce('scripts')
        <script 
            type="text/x-template"
            id="v-quote-template"
        >
            <div class="mt-3.5 flex gap-2.5 max-xl:flex-wrap">
                <div class="flex flex-1 flex-col gap-2 max-xl:flex-auto">
                    <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                        {!! view_render_event('admin.contacts.quotes.edit.form_controls.before') !!}
                       
                        <div class="border-b border-gray-200 text-center text-sm font-medium dark:border-gray-700">
                            <ul class="flex flex-wrap">
                                <template
                                    v-for="tab in tabs"
                                    :key="tab.id"
                                >
                                    <li class="me-2">
                                        <a
                                            :href="'#' + tab.id"
                                            :class="[
                                                'inline-block p-4 rounded-t-lg border-b-2',
                                                activeTab === tab.id
                                                ? 'text-blue-600 border-blue-600 dark:text-blue-500 dark:border-blue-500'
                                                : 'text-gray-600 dark:text-gray-300  border-transparent hover:text-gray-800 hover:border-gray-400 dark:hover:border-gray-400  dark:hover:text-white'
                                            ]"
                                            @click="scrollToSection(tab.id)"
                                            :text="tab.label"
                                        ></a>
                                    </li>
                                </template>
                            </ul>
                        </div>

                        <!-- Quote information -->
                        <div 
                            class="mt-4"
                            id="quote-info"
                        >
                            <div class="mb-4 flex items-center justify-between gap-4">
                                <div class="flex flex-col gap-1">
                                    <p class="text-base font-semibold text-gray-800 dark:text-white">
                                        @lang('admin::app.quotes.create.quote-info')
                                    </p>

                                    <p class="text-sm text-gray-600 dark:text-white">@lang('admin::app.quotes.create.quote-info-info')</p>
                                </div>
                            </div>

                            <div class="w-1/2">
                                <x-admin::attributes
                                    :custom-attributes="app('Webkul\Attribute\Repositories\AttributeRepository')->findWhere([
                                        'entity_type' => 'quotes',
                                        ['code', 'IN', ['subject']],
                                    ])"

                                    :custom-validations="[
                                        'expired_at' => [
                                            'required',
                                            'date_format:yyyy-MM-dd',
                                            'after:' .  \Carbon\Carbon::yesterday()->format('Y-m-d')
                                        ],
                                    ]"
                                />

                                <x-admin::attributes
                                    :custom-attributes="app('Webkul\Attribute\Repositories\AttributeRepository')->findWhere([
                                            'entity_type' => 'quotes',
                                            ['code', 'IN', ['description']],
                                        ])"
                                    :custom-validations="[
                                        'expired_at' => [
                                            'required',
                                            'date_format:yyyy-MM-dd',
                                            'after:' .  \Carbon\Carbon::yesterday()->format('Y-m-d')
                                        ],
                                    ]"
                                />
                                
                                <div class="flex gap-4">
                                    <x-admin::attributes
                                        :custom-attributes="app('Webkul\Attribute\Repositories\AttributeRepository')->findWhere([
                                            'entity_type' => 'quotes',
                                            ['code', 'IN', ['expired_at', 'user_id']],
                                        ])->sortBy('sort_order')"
                                        :custom-validations="[
                                            'expired_at' => [
                                                'required',
                                                'date_format:yyyy-MM-dd',
                                                'after:' .  \Carbon\Carbon::yesterday()->format('Y-m-d')
                                            ],
                                        ]"
                                        :entity="$quote"
                                    />
                                </div>

                                <div class="flex gap-4">
                                    <x-admin::attributes
                                        :custom-attributes="app('Webkul\Attribute\Repositories\AttributeRepository')->findWhere([
                                            'entity_type' => 'quotes',
                                            ['code', 'IN', ['person_id']],
                                        ])->sortBy('sort_order')"
                                        :custom-validations="[
                                            'expired_at' => [
                                                'required',
                                                'date_format:yyyy-MM-dd',
                                                'after:' .  \Carbon\Carbon::yesterday()->format('Y-m-d')
                                            ],
                                        ]"
                                        :entity="$quote"
                                    />
                                    
                                    <x-admin::attributes.edit.lookup />
                                    
                                    @php
                                        $lookUpEntityData = app('Webkul\Attribute\Repositories\AttributeRepository')->getLookUpEntity('leads', request('id'));
                                    @endphp

                                    <x-admin::form.control-group class="w-full">
                                        <x-admin::form.control-group.label>
                                            @lang('admin::app.quotes.create.link-to-lead')
                                        </x-admin::form.control-group.label>
                
                                        <v-lookup-component
                                            :attribute="{'code': 'lead_id', 'name': 'Lead', 'lookup_type': 'leads'}"
                                            :value='@json($lookUpEntityData)'
                                        ></v-lookup-component>
                                    </x-admin::form.control-group>
                                </div>
                            </div>
                        </div>

                        <!-- Address information -->
                        <div
                            class="mt-4"
                            id="address-info"
                        >
                            <div class="mb-4 flex items-center justify-between gap-4">
                                <div class="flex flex-col gap-1">
                                    <p class="text-base font-semibold text-gray-800 dark:text-white">
                                        @lang('admin::app.quotes.create.address-info')
                                    </p>

                                    <p class="text-sm text-gray-600 dark:text-white">@lang('admin::app.quotes.create.address-info-info')</p>
                                </div>
                            </div>

                            <div class="w-1/2">
                                <x-admin::attributes
                                    :custom-attributes="app('Webkul\Attribute\Repositories\AttributeRepository')->findWhere([
                                            'entity_type' => 'quotes',
                                            ['code', 'IN', ['billing_address', 'shipping_address']],
                                        ])"
                                    :entity="$quote"
                                />
                            </div>
                        </div>

                        <!-- Quote Item Information -->
                        <div
                            class="mt-4"
                            id="quote-items"
                        >
                            <div class="mb-4 flex items-center justify-between gap-4">
                                <div class="flex flex-col gap-1">
                                    <p class="text-base font-semibold text-gray-800 dark:text-white">
                                        @lang('admin::app.quotes.create.quote-items')
                                    </p>

                                    <p class="text-sm text-gray-600 dark:text-white">@lang('admin::app.quotes.create.quote-item-info')</p>
                                </div>
                            </div>

                            <!-- Quote Item List Vue Component -->
                            <v-quote-item-list :errors="errors"></v-quote-item-list>
                        </div>

                        {!! view_render_event('admin.contacts.quotes.edit.form_controls.after') !!}
                    </div>
                </div>
            </div>
        </script>

        <script
            type="text/x-template"
            id="v-quote-item-list-template"
        >
            <div class="pb-2">
                <!-- Table -->
                <x-admin::table class="w-full table-fixed">
                    <!-- Table Head -->
                    <x-admin::table.thead class="rounded-lg border border-gray-200 px-4 py-2 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
                        <x-admin::table.thead.tr>
                            <x-admin::table.th >
                                @lang('admin::app.quotes.create.product-name')
                            </x-admin::table.th>
                
                            <x-admin::table.th class="!px-2 ltr:text-right rtl:text-left">
                                @lang('admin::app.quotes.create.quantity')
                            </x-admin::table.th>
                
                            <x-admin::table.th class="!px-2 ltr:text-right rtl:text-left">
                                @lang('admin::app.quotes.create.price')
                            </x-admin::table.th>
                
                            <x-admin::table.th class="!px-2 ltr:text-right rtl:text-left">
                                @lang('admin::app.quotes.create.amount')
                            </x-admin::table.th>
                
                            <x-admin::table.th class="!px-2 ltr:text-right rtl:text-left">
                                @lang('admin::app.quotes.create.discount')
                            </x-admin::table.th>
                
                            <x-admin::table.th class="!px-2 ltr:text-right rtl:text-left">
                                @lang('admin::app.quotes.create.tax')
                            </x-admin::table.th>
                
                            <x-admin::table.th class="!px-2 ltr:text-right rtl:text-left">
                                @lang('admin::app.quotes.create.total')
                            </x-admin::table.th>

                            <x-admin::table.th 
                                v-if="products.length > 1"
                                class="!px-2 ltr:text-right rtl:text-left"
                            >
                                @lang('admin::app.quotes.create.action')
                            </x-admin::table.th>
                        </x-admin::table.thead.tr>
                    </x-admin::table.thead>

                    <!-- Table Body -->
                    <x-admin::table.tbody class="rounded-lg border border-gray-200 bg-gray-500 px-4 py-2 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
                        <!-- Quote Item Vue component -->
                        <template
                            v-for='(product, index) in products'
                            :key="index"
                        >
                            <v-quote-item
                                :product="product"
                                :index="index"
                                :errors="errors"
                                @onRemoveProduct="removeProduct($event)"
                            ></v-quote-item>
                        </template>
                    </x-admin::table.tbody>
                </x-admin::table>
            </div>

            <!-- Add New Qoute Item -->
            <span
                class="text-md cursor-pointer text-brandColor hover:underline dark:text-brandColor"
                @click="addProduct"
            >
                @lang('admin::app.quotes.create.add-item')
            </span>

            <div class="mt-8 flex items-start gap-10 max-lg:gap-5">
                <div class="flex-auto">
                    <div class="flex justify-end">
                        <div class="grid w-[348px] gap-4 rounded-lg bg-gray-100 p-4 text-sm dark:bg-gray-950 dark:text-white">
                            <div class="flex w-full justify-between gap-x-5">
                                @lang('admin::app.quotes.create.sub-total', ['symbol' => core()->currencySymbol(config('app.currency'))])

                                <input
                                    type="hidden"
                                    name="sub_total"
                                    class="control"
                                    :value="subTotal"
                                    readonly
                                >

                                <p>@{{ subTotal }}</p>
                            </div>

                            <div class="flex w-full justify-between gap-x-5">
                                @lang('admin::app.quotes.create.total-discount', ['symbol' => core()->currencySymbol(config('app.currency'))])

                                <input
                                    type="hidden"
                                    name="discount_amount"
                                    :value="discountAmount"
                                >

                                <p>@{{ discountAmount }}</p>
                            </div>

                            <div class="flex w-full justify-between gap-x-5">
                                @lang('admin::app.quotes.create.total-tax', ['symbol' => core()->currencySymbol(config('app.currency'))])

                                <input
                                    type="hidden"
                                    name="tax_amount"
                                    :value="taxAmount"
                                >

                                <p>@{{ taxAmount }}</p>
                            </div>

                            <div class="flex w-full justify-between gap-x-5">
                                @lang('admin::app.quotes.create.total-adjustment', ['symbol' => core()->currencySymbol(config('app.currency'))])

                                <x-admin::form.control-group.control
                                    type="inline"
                                    ::name="`adjustment_amount`"
                                    ::value="adjustmentAmount"
                                    rules="required|decimal:4"
                                    ::errors="errors"
                                    :label="trans('admin::app.quotes.create.adjustment-amount')"
                                    :placeholder="trans('admin::app.quotes.create.adjustment-amount')"
                                    @on-change="(event) => adjustmentAmount = event.value"
                                />
                            </div>

                            <div class="flex w-full justify-between gap-x-5">
                                @lang('admin::app.quotes.create.grand-total', ['symbol' => core()->currencySymbol(config('app.currency'))])

                                <input
                                    type="hidden"
                                    name="grand_total"
                                    :value="grandTotal"
                                >

                                <p>@{{ grandTotal }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </script>

        <script
            type="text/x-template"
            id="v-quote-item-template"
        >
            <x-admin::table.thead.tr class="border-b-2">
                <!-- Quote Product Name -->
                <x-admin::table.td>
                    <x-admin::form.control-group class="!mb-0">
                        <x-admin::lookup 
                            ::src="src"
                            ::name="`${inputName}[product_id]`"
                            :placeholder="trans('admin::app.quotes.create.search-products')"
                            @on-selected="(product) => addProduct(product)"
                        />
                    </x-admin::form.control-group>
                </x-admin::table.td>
            
                <!-- Quantity -->
                <x-admin::table.td class="!px-2 ltr:text-right rtl:text-left">
                    <x-admin::form.control-group class="!mb-0">
                        <x-admin::form.control-group.control
                            type="inline"
                            ::name="`${inputName}[quantity]`"
                            ::value="product.quantity"
                            rules="required|decimal:4"
                            ::errors="errors"
                            :label="trans('admin::app.quotes.create.quantity')"
                            :placeholder="trans('admin::app.quotes.create.quantity')"
                            @on-change="(event) => product.quantity = event.value"
                        />
                    </x-admin::form.control-group>
                </x-admin::table.td>
            
                <!-- Price -->
                <x-admin::table.td class="!px-2 ltr:text-right rtl:text-left">
                    <x-admin::form.control-group class="!mb-0">
                        <x-admin::form.control-group.control
                            type="inline"
                            ::name="`${inputName}[price]`"
                            ::value="product.price"
                            rules="required|decimal:4"
                            ::errors="errors"
                            :label="trans('admin::app.quotes.create.price')"
                            :placeholder="trans('admin::app.quotes.create.price')"
                            @on-change="(event) => product.price = event.value"
                        />
                    </x-admin::form.control-group>
                </x-admin::table.td>
            
                <!-- Total -->
                <x-admin::table.td class="!px-2 ltr:text-right rtl:text-left">
                    <x-admin::form.control-group class="!mb-0">
                        <x-admin::form.control-group.control
                            type="inline"
                            ::name="`${inputName}[total]`"
                            ::value="product.price * product.quantity"
                            rules="required|decimal:4"
                            ::errors="errors"
                            :label="trans('admin::app.quotes.create.total')"
                            :placeholder="trans('admin::app.quotes.create.total')"
                            ::allowEdit="false"
                        />
                    </x-admin::form.control-group>
                </x-admin::table.td>
            
                <!-- Discount Amount -->
                <x-admin::table.td class="!px-2 ltr:text-right rtl:text-left">
                    <x-admin::form.control-group class="!mb-0">
                        <x-admin::form.control-group.control
                            type="inline"
                            ::name="`${inputName}[discount_amount]`"
                            ::value="product.discount_amount"
                            rules="required|decimal:4"
                            ::errors="errors"
                            :label="trans('admin::app.quotes.create.discount-amount')"
                            :placeholder="trans('admin::app.quotes.create.discount-amount')"
                            @on-change="(event) => product.discount_amount = event.value"
                        />
                    </x-admin::form.control-group>
                </x-admin::table.td>
            
                <!-- Tax Amount -->
                <x-admin::table.td class="!px-2 ltr:text-right rtl:text-left">
                    <x-admin::form.control-group class="!mb-0">
                        <x-admin::form.control-group.control
                            type="inline"
                            ::name="`${inputName}[tax_amount]`"
                            ::value="product.tax_amount"
                            rules="required|decimal:4"
                            ::errors="errors"
                            :label="trans('admin::app.quotes.create.tax-amount')"
                            :placeholder="trans('admin::app.quotes.create.tax-amount')"
                            @on-change="(event) => product.tax_amount = event.value"
                        />
                    </x-admin::form.control-group>
                </x-admin::table.td>
            
                <!-- Total with Discount -->
                <x-admin::table.td class="!px-2 ltr:text-right rtl:text-left">
                    <x-admin::form.control-group class="!mb-0">
                        <x-admin::form.control-group.control
                            type="inline"
                            ::name="`${inputName}[final_total]`"
                            ::errors="errors"
                            ::value="parseFloat(product.price * product.quantity) + parseFloat(product.tax_amount) - parseFloat(product.discount_amount)"
                            ::allowEdit="false"
                        />
                    </x-admin::form.control-group>
                </x-admin::table.td>

                <!-- Action -->
                <x-admin::table.td
                    v-if="$parent.products.length > 1"
                    class="!px-2 ltr:text-right rtl:text-left"
                >
                    <x-admin::form.control-group class="!mb-0">
                        <i  
                            @click="removeProduct"
                            class="icon-delete cursor-pointer text-2xl"
                        ></i>
                    </x-admin::form.control-group>
                </x-admin::table.td>
            </x-admin::table.thead.tr>
        </script>

        <script type="module">
            app.component('v-quote', {
                template: '#v-quote-template',

                props: ['errors'],

                data() {
                    return {
                        activeTab: 'quote-info',

                        tabs: [
                            { id: 'quote-info', label: '@lang('admin::app.quotes.create.quote-info')' },
                            { id: 'address-info', label: '@lang('admin::app.quotes.create.address-info')' },
                            { id: 'quote-items', label: '@lang('admin::app.quotes.create.quote-items')' }
                        ],
                    };
                },

                methods: {
                    /**
                     * Scroll to the section.
                     * 
                     * @param {String} tabId
                     * 
                     * @returns {void}
                     */
                    scrollToSection(tabId) {
                        const section = document.getElementById(tabId);

                        if (section) {
                            section.scrollIntoView({ behavior: 'smooth' });
                        }
                    },
                },
            });

            app.component('v-quote-item-list', {
                template: '#v-quote-item-list-template',

                props: ['data', 'errors'],
                
                data() {
                    return {
                        adjustmentAmount: 0,

                        products: [{
                            'id': null,
                            'product_id': null,
                            'name': '',
                            'quantity': 0,
                            'price': 0,
                            'discount_amount': 0,
                            'tax_amount': 0,
                        }],
                    }
                },

                computed: {
                    /**
                     * Calculate the sub total of the products.
                     * 
                     * @returns {Number}
                     */
                    subTotal() {
                        let total = 0;

                        this.products.forEach(product => {
                            total += parseFloat(product.price * product.quantity);
                        });

                        return total;
                    },

                    /**
                     * Calculate the total discount amount of the products.
                     * 
                     * @returns {Number}
                     */
                    discountAmount() {
                        let total = 0;

                        this.products.forEach(product => total += parseFloat(product.discount_amount));

                        return total;
                    },

                    /**
                     * Calculate the total tax amount of the products.
                     * 
                     * @returns {Number}
                     */
                    taxAmount() {
                        let total = 0;

                        this.products.forEach(product => total += parseFloat(product.tax_amount));

                        return total;
                    },

                    /**
                     * Calculate the grand total of the products.
                     * 
                     * @returns {Number}
                     */
                    grandTotal() {
                        let total = 0;

                        this.products.forEach(product => {
                            total += parseFloat(product.price * product.quantity) + parseFloat(product.tax_amount) - parseFloat(product.discount_amount) + parseFloat(this.adjustmentAmount);
                        });

                        return total;
                    },
                },

                methods: {
                    /**
                     * Add a new product.
                     * 
                     * @returns {void}
                     */
                    addProduct() {
                        this.products.push({
                            id: null,
                            product_id: null,
                            name: '',
                            quantity: 1,
                            total: 0,
                            price: 0,
                            discount_amount: 0,
                            tax_amount: 0,
                        });
                    },

                    /**
                     * Remove the product.
                     * 
                     * @param {Object} product
                     */
                    removeProduct(product) {
                        this.$emitter.emit('open-confirm-modal', {
                            agree: () => {
                                if (this.products.length === 1) {
                                    this.products = [{
                                        id: null,
                                        product_id: null,
                                        name: '',
                                        quantity: null,
                                        total: 0,
                                        price: null,
                                        discount_amount: null,
                                        tax_amount: null,
                                    }];
                                } else {
                                    const index = this.products.indexOf(product);

                                    if (index !== -1) {
                                        this.products.splice(index, 1);
                                    }
                                }
                            },
                        });
                    },
                },
            });

            app.component('v-quote-item', {
                template: '#v-quote-item-template',

                props: ['index', 'product', 'errors'],

                data() {
                    return {
                        products: [],
                    }
                },

                computed: {
                    /**
                     * Get the input name.
                     * 
                     * @returns {String}
                     */
                    inputName() {
                        if (this.product.id) {
                            return "items[" + this.product.id + "]";
                        }

                        return "items[item_" + this.index + "]";
                    },

                    /**
                     * Get the source URL.
                     * 
                     * @returns {String}
                     */
                    src() {
                        return "{{ route('admin.products.search') }}";
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
                        this.product.quantity = result.quantity ?? 1;
                        this.product.discount_amount = 0;
                        this.product.tax_amount = 0;
                    },

                    /**
                     * Remove the product.
                     * 
                     * @return {void}
                     */
                    removeProduct() {
                        this.$emit('onRemoveProduct', this.product);
                    },
                },
            });
        </script>
    @endPushOnce

    @pushOnce('styles')
        <style>
            html {
                scroll-behavior: smooth;
            }
        </style>
    @endPushOnce    
</x-admin::layouts>
