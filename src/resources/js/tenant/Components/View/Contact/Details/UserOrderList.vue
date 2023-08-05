<template>
    <div>
        <app-table :id="table_id" :options="options" @action=""></app-table>
    </div>
</template>
<script>
import HelperMixin from "../../../../../common/Mixin/Global/HelperMixin";
import OrderListMixin from "../../../Mixins/OrderListMixin";
export default {
    name: "OrderList",
    props: {
        props: {

        }
    },
    mixins: [HelperMixin, OrderListMixin],
    data() {
        return {
            table_id: 'user-customer-order-table',
        };
    },
}
</script>