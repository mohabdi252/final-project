<template>
    <div class="content-wrapper">

      <app-page-top-section :title="$t('counter')" icon="briefcase">
        <app-default-button
            v-if="this.$can('view_counter')"
            @click="openModal()"
            :title="$fieldTitle('add', 'counter', true)"/>
      </app-page-top-section>

      <app-table
            id="counter-table"
            :options="options"
            @action="triggerActions"
        />

        <app-counter-modal
            v-if="isModalActive"
            v-model="isModalActive"
            :selected-url="selectedUrl"
            @close="isModalActive = false"

        />

        <app-confirmation-modal
            v-if="confirmationModalActive"
            icon="trash-2"
            modal-id="app-confirmation-modal"
            @confirmed="confirmed('counter-table')"
            @cancelled="cancelled"
        />
    </div>

</template>

<script>
import HelperMixin from "../../../../common/Mixin/Global/HelperMixin";
import CounterMixin from "../../Mixins/CounterMixin";
import {COUNTERS} from "../../../Config/ApiUrl-CPB";
import CounterAddEditModal from "./CounterAddEditModal";

export default {
    name: "Counter",
    components: {CounterAddEditModal},
    mixins: [HelperMixin, CounterMixin],
    props: ['id'],
    data() {
        return {
            isModalActive: false,
            selectedUrl: '',
        }
    },
    mounted() {
        this.$hub.$on('headerButtonClicked-' + this.id, (component) => {
            this.openModal();
        })
    },
    methods: {
        triggerActions(row, action, active) {
            if (action.title === this.$t('edit')) {
                this.selectedUrl = `${COUNTERS}/${row.id}`;
                this.isModalActive = true;
            } else {
                this.getAction(row, action, active)
            }
        },
        openModal() {
            this.isModalActive = true;
            this.selectedUrl = ''
        },
    },
    beforeDestroy() {
        this.$hub.$off('headerButtonClicked-' + this.id);
    }
}
</script>