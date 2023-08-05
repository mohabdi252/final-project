import DatatableHelperMixin from "../../../../../common/Mixin/Global/DatatableHelperMixin";
import {EXPENSE_REPORT} from "../../../../Config/ApiUrl-CP";
import {numberWithCurrencySymbol} from "../../../../Helper/Helper";
import {formatDateToLocal} from "../../../../../common/Helper/Support/DateTimeHelper";
import {textTruncate} from "../../../../../common/Helper/Support/TextHelper";
import {expense_area} from "../../../../../store/Tenant/Mixins/ExpenseAreaMixin";
import {mapGetters} from "vuex";
import {urlGenerator} from "../../../../../common/Helper/AxiosHelper";

export default {
    mixins: [DatatableHelperMixin, expense_area],
    data() {
        return {
            options: {
                name: this.$t('expense'),
                url: null,
                showCount: true,
                showClearFilter: true,
                showHeader: true,
                columns: [
                    {
                        title: this.$t('name'),
                        type: 'custom-html',
                        key: 'name',
                        modifier: value => `<p class="pb-0">${value ?? '-'}</p>`
                    },
                    {
                        title: this.$t('area_of_expense'),
                        type: 'custom-html',
                        key: 'expense_area',
                        modifier: value => `<p class="pb-0">${value ? value.name : '-'}</p>`
                    },
                    {
                        title: this.$t('branch_or_warehouse'),
                        type: 'custom-html',
                        key: 'branch_or_warehouse',
                        modifier: value => value ? `<p class="pb-0">${value.name} <span class="text-${value.type.toLowerCase() === 'branch' ? 'warning' : 'info'}">(${value.type[0].toUpperCase()})</span></p>` : ''
                    },
                    {
                        title: this.$t('amount'),
                        type: 'custom-html',
                        key: 'amount',
                        titleAlignment: 'right',
                        modifier: value => `<p class="text-right pb-0">${numberWithCurrencySymbol(value)}</p>`
                    },
                    {
                        title: this.$t('expense_date'),
                        type: 'custom-html',
                        key: 'expense_date',
                        isVisible: true,
                        modifier: expense_date => expense_date ? `<p class="pb-0">${formatDateToLocal(expense_date)}</p>` : '-'
                    },
                    {
                        title: this.$t('description'),
                        type: 'custom-html',
                        key: 'description',
                        modifier: value => value ? `<p class="pb-0">${textTruncate(value, 80)}</p>` : '-'
                    },
                    {
                        title: this.$t('attachment'),
                        type: 'component',
                        key: 'attachments',
                        componentName: 'app-attachment'
                    },
                ],
                actionType: "default",
                actions: [
                ],
                filters: [
                    {
                        title: this.$t('expense_date'),
                        type: "range-picker",
                        key: "expense_date",
                        option: ["today", "thisMonth", "last7Days", "thisYear"]
                    },
                    {
                        title: this.$t('area_of_expense'),
                        type: "multi-select-filter",
                        key: "expense_area",
                        option: [],
                    },
                ],
                paginationType: "pagination",
                responsive: true,
                rowLimit: 10,
                showAction: true,
                orderBy: 'desc',
                search: true,
            },
        }
    },

    computed: {
        ...mapGetters(['getBranchOrWarehouseId']),
    },
    mounted() {
        this.updateUrl(EXPENSE_REPORT);
    },
    watch: {
        getBranchOrWarehouseId(new_id) {
            this.updateUrl(EXPENSE_REPORT);
        },
    },
}
