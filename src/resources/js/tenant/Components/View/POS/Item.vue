<template>
    <div
        :class="`
            card card-with-shadow sale-item rounded d-flex flex-column position-relative
            cursor-pointer ${product.product_selected ? 'item-in-cart-styles' : ''} ${product.available_qty <= 0 ? 'out_of_stock' : ''}
        `"
        @click="handleSaleItemClick"
    >
        <img
            :src="productImage || defaultImage"
            class="product-image align-self-center mb-3"
            alt="Item Image"
        />
        <p class="stock-out-alert"> {{ $t('stock_out') }} </p>
        <p class="item-in-cart-alert"> {{ $t('already_in_cart') }} </p>
        <div class="item-data">
            <p class="item-name mb-0" :title="product.variant.name">{{ product.variant.name | truncate }}</p>
            <small class="item-upc text-muted"># {{ product.variant.upc }}</small>
            <p class="item-price text-primary mb-0">{{ numberWithCurrencySymbol(product.variant.selling_price) }}</p>
        </div>
    </div>
</template>

<script>
import { mapActions } from "vuex";
import {numberWithCurrencySymbol, urlGenerator} from "../../../Helper/Helper";

export default {
    name: 'Item',
    props: ['product'],
    filters: {
        truncate(value) {
            const limit = 30;
            if (value.length < limit) return value;
            return value.substring(0, limit) + '...'
        }
    },
    computed: {
        productImage() {
           let product_type = this.product?.variant?.product?.product_type;
           if (product_type === 'single') return this.product?.variant?.product?.thumbnail?.full_url ?? this.defaultImage;
           if (product_type === 'variant') return this.product?.variant?.thumbnail?.full_url ?? this.defaultImage;
        },
        defaultImage() {
          return urlGenerator('storage/product/default_product.png');
          return urlGenerator('images/product/default_product.png');
        },
    },
  methods: {
        ...mapActions(['addItemToCart']),
        handleSaleItemClick() {
            if (this.product.available_qty <= 0) return this.$toastr.e(this.$t('no_more_items_in_stock'));
            this.addItemToCart({ ...this.product, price: this.product.variant.selling_price });
        },
        numberWithCurrencySymbol(value) {
            return numberWithCurrencySymbol(value);
        }
    },
}
</script>

<style lang="sass" scoped>
$purple: #4466f2
@mixin info-badge
    display: block
    top: 32%
    left: 50%
    transform: translate(-50%, -50%)
    position: absolute
    text-align: center
    color: white
    border-radius: .25rem
    padding: .25rem 1rem

.item-data
    position: absolute
    bottom: 0
    padding: 10px

.sale-item
    box-sizing: border-box
    padding-bottom: 6rem
    transition: 150ms

    &:hover
        border: 1px solid #4466f2 !important

        .product-image
            opacity: 1
            transform: scale(1)

    .product-image
        $imgDimension: 6rem
        $scaleDownBy: 0.85

        opacity: $scaleDownBy
        transition: inherit
        transform: scale($scaleDownBy)
        width: $imgDimension
        height: $imgDimension
        margin-top: .875rem

.stock-out-alert, .item-in-cart-alert
    display: none

.item-in-cart-styles
    border: 2px solid #4466f2 !important

    .item-in-cart-alert
        @include info-badge
        padding: .25rem 1rem
        width: 10rem
        background: $purple


.out_of_stock
    position: relative
    pointer-events: none

    .stock-out-alert
        @include info-badge
        background: red
</style>
