<template>
    <section class="sales-view content-wrapper">
        <pos-view-top-section/>
        <div class="view-body row">
            <product-input @filter-btn-click="handleFilterBtnClickEvent" :view-mode="viewMode"/>
            <customer-input :view-mode="viewMode" v-if="showCustomerInput" :key="getCustomerInputKey"/>

            <cash-register-selector-modal v-if="showCashRegisterSelectModal"
                                          @close="handleCashRegisterSelectorModalClose"/>

            <div :class="`${viewMode === 'primary' ? 'col-7 custom-resize' : 'col-6'} mt-4`">
                <filter-display v-if="showFilterDisplay" @clear-filter-click="handleClearFilterClickEvent"/>

                <!-- Skeletons -->
                <template v-if="saleViewProducts === null">
                    <div class="item-display">
                        <div v-for="i in 20" class="card card-with-shadow rounded d-flex flex-column mb-3 animate-pulse"
                             style="width: 165px; height: 270px"></div>
                    </div>
                </template>

                <template v-else> <!--if saleViewProducts is an array-->
                    <template v-if="!Boolean(saleViewProducts.length)"> <!--if saleViewProducts is empty-->
                        <div class="empty-message d-flex flex-column align-items-center justify-content-around"
                             style="margin-top: 50%;" v-if="!cartItems.length">
                            <div class="mb-5" style="opacity: 0.5;">
                                <app-icon name="shopping-bag" class="text-danger opacity-50"
                                          style="transform: scale(3.5)"/>
                            </div>
                            <h4 class="text-muted">{{ $t('no_items_to_sell') }}</h4>
                        </div>
                    </template>

                    <!-- if saleViewProducts[] has elements in it -->
                    <template v-else>
                        <div class="item-display" v-if="viewMode === 'primary'">
                            <sale-item v-for="(product, index) in saleViewProducts" :key="index"
                                       :product="product"/>
                        </div>
                        <template v-else> <!-- barcode view -->
                            <div :class="`labels col-12 row border-bottom no-gutters mb-3`">
                                <small class="col-4">{{ $t('selected_product') }}</small>
                                <small class="col-4">{{ $t('quantity') }}</small>
                                <small class="col-3">{{ $t('sub_total') }}</small>
                                <small class="col-1"></small>
                            </div>
                            <div class="barcode-view rounded">
                                <cart-item v-for="item in cartItems" :key="item.upc" :item="item"/>
                            </div>
                        </template>
                    </template>
                </template>


                <div v-else>
                    <app-cart/>
                </div>
            </div>

            <div :class="`${viewMode === 'primary' ? 'col-5 custom-resize' : 'col-6'} mt-4`">
                <div>
                    <app-cart/>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import FormHelperMixins from "../../../../common/Mixin/Global/FormHelperMixins";
import {urlGenerator} from "../../../../common/Helper/AxiosHelper";

import POSTopSection from './POSTopSection';
import ProductInput from './ProductInput';
import CustomerInput from './CustomerInput';
import CashRegisterSelectModal from './CashRegisterSelectorModal';
import BranchOrWarehouseSelectorModal from './BranchOrWarehouseSelectorModal';
import Item from './Item';
import Cart from './Cart/Cart';
import FilterDisplay from "./FilterDisplay";
import {mapActions, mapGetters, mapMutations} from "vuex";
import CartDetails from "./Cart/CartDetails";
import CartItem from "./Cart/CartItem";
import CartControl from "./Cart/CartControl";

export default {
    mixins: [FormHelperMixins],
    components: {
        'pos-view-top-section': POSTopSection,
        'product-input': ProductInput,
        'customer-input': CustomerInput,
        'sale-item': Item,
        'app-cart': Cart,
        'cart-item': CartItem,
        'filter-display': FilterDisplay,
        'cart-details': CartDetails,
        'cash-register-selector-modal': CashRegisterSelectModal,
        'branch-or-warehouse-select-modal': BranchOrWarehouseSelectorModal,
        'cart-control': CartControl
    },
    name: 'AppSalesView',
    data() {
        return {
            showCustomerInput: false,
            showFilterDisplay: false,
            showCashRegisterSelectModal: true,
            // showBranchOrWarehouseSelectorModal: true,
            productOptions: {
                url: urlGenerator("app/selectable-variants"),
                query_name: "search_by_name",
                per_page: 10,
                loader: "app-pre-loader",
                modifire: ({id, name: value}) => ({id, value}),
            },
        }
    },
    computed: {
        ...mapGetters(['saleViewProducts', 'cartItems', 'viewMode', 'getBranchOrWarehouseId', 'getState', 'getCashRegisterId', 'getSelectedCustomer', 'getCustomerInputKey']),
    },
    watch: {
        cartItems: {
            immediate: true,
            handler: function (newCartItems) {
                if (newCartItems.length) return;
                this.SET_SUBTOTAL(0);
            }
        },
        getState: {
            immediate: false,
            deep: true,
            handler: function (cartState) {
                localStorage.setItem('cartState', JSON.stringify(cartState));
            }
        },
        getBranchOrWarehouseId: {
            immediate: true,
            handler(newId) {
               if (!newId) return;
               this.setProducts();
            }
        },
    },
    created() {
        const previouslyOpenedCashRegisterId = localStorage.getItem('cash_register_id');
        if (previouslyOpenedCashRegisterId) {
            // disabling the cash register and branch selector modal if user has a cash_register opened
            this.setCashRegisterId(previouslyOpenedCashRegisterId);
            this.showCashRegisterSelectModal = false;
        }

        this.$hub.$on('open_cash_register_selector_modal', () => {
            this.showCashRegisterSelectModal = true
        });
    },
    mounted() {
        const stateInLocalStorage = localStorage.getItem('cartState');
        if (stateInLocalStorage) this.setCart(stateInLocalStorage);
        this.showCustomerInput = true;
    },
    methods: {
        ...mapMutations(['SET_SUBTOTAL', 'SET_ORDER_RESUME_STATE', 'INCREMENT_CUSTOMER_INPUT_KEY']),
        ...mapActions(['setProducts', 'setHoldOrders', 'setCart', 'setCashRegisterId']),
        handleCashRegisterSelectorModalClose() {
            this.showCashRegisterSelectModal = false;
        },
        handleFilterBtnClickEvent() {
            this.showFilterDisplay = true;
        },
        handleClearFilterClickEvent() {
            this.showFilterDisplay = false;
        },
    }
}
</script>

<style lang="sass">
.item-display
    height: 86vh
    display: grid
    grid-template-columns: repeat(4, 1fr)
    grid-template-rows: repeat(4, 1fr)
    justify-items: stretch
    align-items: stretch
    grid-gap: 1.5rem
    overflow-y: scroll
    overflow-x: hidden
    padding-bottom: 5rem
    -ms-overflow-style: none
    scrollbar-width: 0.25vw
    scrollbar-color: #4466F255 #99999900

    &::-webkit-scrollbar
        width: 0.25vw

    &::-webkit-scrollbar-track
        background-color: #99999900

    &::-webkit-scrollbar-thumb
        background-color: #4466F255
        border-radius: 50px

.arrows-hidden input::-webkit-outer-spin-button,
.arrows-hidden input::-webkit-inner-spin-button
    -webkit-appearance: none
    margin: 0

.arrows-hidden input[type=number]
    -moz-appearance: textfield

.animate-pulse
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite

@keyframes pulse
    0%, 100%
        opacity: 1
    50%
        opacity: 0.5

@media only screen and (min-width: 1920px)
    .item-display
        grid-template-columns: repeat(5, 1fr)
        grid-gap: 0.65rem

.default-base-color
    background-color: var(--default-card-bg)

.barcode-view
    height: 75vh
    padding-bottom: 5rem
    overflow-y: scroll
    overflow-x: hidden
    -ms-overflow-style: none
    scrollbar-width: none

    &::-webkit-scrollbar
        display: none

@media only screen and (max-width: 1440px)
    .custom-resize
        flex: 0 0 50%
        max-width: 50%

        .item-display
            grid-template-columns: repeat(3, 1fr)

</style>
