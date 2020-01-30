<template>
    <table class="table table-striped">
        <thead>
        <tr>
            <th
                v-for="column in columns"
                :class="[
                    'vx-sortable-header',
                    column.cssClass,
                    sortColumn === column ? sortDir : null,
                    column.width
                ]"
                @click="column.sortable ? clickSort(column) : null"
            >
                <slot :name="column.prop + '-header'" :column="column">
                    {{ column.label }}
                </slot>
            </th>
        </tr>
        </thead>
        <tbody>
            <tr v-for="row in rows" :key="row.key" :class="row.cssClass">
                <td v-for="column in columns" :class="{ 'active': sortColumn === column }"><slot :name="column.prop" :row="row">{{ row[column.prop] }}</slot></td>
            </tr>
        </tbody>
    </table>
</template>

<script>
    export default {
        name: 'sortable',

        props: {
            columns: {
                type: Array,
                required: true,
                validator (val) {
                    for(let l = val.length; l--;) {
                        if(val[l].label === undefined || val[l].prop === undefined) {
                            return false;
                        }
                    }
                    return true;
                }
            },
            rows: {
                type: Array,
                required: true
            },
            sortProp: {
                type: String
            },
            sortDirection: {
                type: String,
                validator (val) {
                    return !val || ['asc', 'desc'].indexOf(val) !== -1;
                }
            }
        },

        data() {
            return {
                sortColumn: this.columns.find(item => item.prop === this.sortProp),
                sortDir: this.sortDirection
            };
        },

        watch: {
            sortColumn (newVal) {
                this.doSort(newVal, this.sortDir);
            },
            sortDir (newVal) {
                this.doSort(this.sortColumn, newVal);
            },
            rows (newValue) {
                this.doSort(this.sortColumn, this.sortDir);
            }
        },

        methods: {
            clickSort (column) {
                if(this.sortColumn === column) {
                    this.sortDir = this.sortDir === 'asc' ? 'desc' : 'asc';
                }
                else {
                    this.sortColumn = column;
                }
            },
            doSort (column, dir) {
                this.$emit('before-sort');

                if (dir === 'asc' && column.sortAscFunction) {
                    this.rows.sort (column.sortAscFunction);
                }
                else if (dir === 'desc' && column.sortDescFunction) {
                    this.rows.sort (column.sortDescFunction);
                }
                else {
                    let prop = column.prop;

                    this.rows.sort((a, b) => {
                        if (a[prop] < b[prop]) {
                            return dir === "asc" ? -1 : 1;
                        }
                        if (a[prop] > b[prop]) {
                            return dir === "asc" ? 1 : -1;
                        }
                        return 0;
                    });
                }

                this.$emit('after-sort');
            }
        }
    }
</script>