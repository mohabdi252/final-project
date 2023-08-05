<template>
    <div :class="`product-input ${ viewMode === 'primary' ? 'col-7 custom-resize' : 'col-6' } d-flex`">
        <app-input
            v-show="viewMode === 'primary'"
            class="mr-2"
            v-if="productOptions"
            style="flex: 1;"
            type="search-and-select"
            :placeholder="$placeholder('product')"
            :options="productOptions"
            v-model="selectedProduct"
            :key="productInputKey"
        />

        <app-input
            v-else
            class="mr-2"
            style="flex: 1;"
            v-model="selectedProduct"
            type="text"
            :placeholder="$placeholder('product')"
        />

        <app-input
            v-show="viewMode !== 'primary'"
            :autofocus="true"
            class="mr-2"
            style="flex: 1;"
            type="text"
            v-model="productUpc"
            @keyup.enter="handleBarcodeScan"
            :placeholder="$placeholder('product_upc')"
        />
        <button class="btn btn-primary" @click="filterBtnClick">
            <app-icon name="filter"/>
        </button>
    </div>
</template>

<script>
import FormHelperMixins from "../../../../common/Mixin/Global/FormHelperMixins";
import {axiosGet, urlGenerator} from "../../../../common/Helper/AxiosHelper";
import {GET_VARIANT_BY_BARCODE, SALES_VIEW_PRODUCTS, STOCK_VARIANT_WITH_BRANCH} from "../../../Config/ApiUrl-CP";
import {mapActions, mapGetters} from "vuex";

export default {
    mixins: [FormHelperMixins],
    name: 'ProductInput',
    props: ['viewMode'],
    data() {
        return {
            selectedProduct: '',
            productInputKey: 0,
            productUpc: '',
        }
    },
    watch: {
        async 'selectedProduct'(newVariantId) {
            if (!+newVariantId) return;
            const itemData = this.saleViewProducts.find(item => +item.variant.id === +newVariantId);
            if (itemData.available_qty <= 0) return this.$toastr.e(this.$t('item_out_of_stock'));
            this.addItemToCart(itemData);
            /* resetting the input */
            this.selectedProduct = '';
				this.productInputKey++;
        }
    },
    computed: {
        ...mapGetters(['getBranchOrWarehouseId', 'saleViewProducts']),
        productOptions() {
            if (!this.getBranchOrWarehouseId) return null;
            return {
                url: urlGenerator(`${SALES_VIEW_PRODUCTS}?branch_or_warehouse_id=${this.getBranchOrWarehouseId}`),
                query_name: "search",
                per_page: 10,
                loader: "app-pre-loader", // by default 'app-overlay-loader'
                modifire: (data) => ({id: data.variant.id, value: data.variant.name}) 
            }
        },
    },
    methods: {
        ...mapActions(['addItemToCart']),
        async handleBarcodeScan() {
            const itemData = this.saleViewProducts.find(item => item.variant.upc.toString() === this.productUpc.toString());
            if (itemData) {
                if (itemData.available_qty <= 0) return this.$toastr.e(this.$t('item_out_of_stock'));
                this.clearProductUpc();
                return this.addItemToCart(itemData)
            };
            this.$toastr.e(this.$t('item_not_found'));
            return setTimeout(() => {
                this.clearProductUpc();
            }, 1500)
        },
        clearProductUpc() {
            this.productUpc = '';
        },
        filterBtnClick() {
            this.$emit("filter-btn-click");
        }
    }
}
</script>
