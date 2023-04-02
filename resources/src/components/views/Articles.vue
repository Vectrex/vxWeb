<script setup>
  import Sortable from "@/components/vx-vue/sortable.vue";
  import Pagination from "@/components/vx-vue/pagination.vue";
  import FormSwitch from "@/components/vx-vue/form-switch.vue";
  import FilterForm from "@/components/views/articles/FilterForm.vue";
  import Alert from "@/components/vx-vue/alert.vue";
  import Headline from "@/components/app/Headline.vue";
  import { PencilSquareIcon, TrashIcon, PlusIcon } from '@heroicons/vue/24/solid';
</script>

<template>
  <teleport to="#tools">
    <headline><span>Artikel</span>
      <button
          class="icon-link !text-vxvue-700 border-transparent !hover:border-vxvue-700"
          type="button"
          @click="$router.push({ name: 'articleEdit' })"
      >
        <plus-icon class="w-5 h-5" />
      </button>
    </headline>
  </teleport>

  <div class="h-16 flex items-center justify-between">
    <filter-form v-model="filter" :options="{ categories: [ { key: 0, label: '(Alle Kategorien)' }, ...categories ] }" />
    <pagination
        class="w-1/4"
        :total="filteredArticles.length"
        :per-page="pagination.entriesPerPage"
        next-text=""
        prev-text=""
        v-model:page="pagination.page"
        marker-position="below"
    />
  </div>
  <sortable
      :rows="paginatedArticles"
      :columns="cols"
      :sort-prop="initSort.column"
      :sort-direction="initSort.dir"
      :offset="(currentPage - 1) * entriesPerPage"
      :count="entriesPerPage"
      @after-sort="storeSort"
  >
    <template v-slot:catId="slotProps">
      {{ categories.find(c => c.id === slotProps.row.catId).label }}
    </template>
    <template v-slot:pub="slotProps">
      <form-switch :model-value="slotProps.row.pub" @update:model-value="publish(slotProps.row)" />
    </template>
    <template v-slot:marked="slotProps">
      <input type="checkbox" class="form-checkbox text-slate-500" disabled="disabled" :checked="slotProps.row.marked">
    </template>
    <template v-slot:action="slotProps">
      <div class="flex items-center space-x-1 justify-end">
        <router-link :to="{ name: 'articleEdit', params: { id: slotProps.row.id } }" class="icon-link tooltip" data-tooltip="Bearbeiten">
          <pencil-square-icon class="h-5 w-5" />
        </router-link>
        <button class="icon-link tooltip" data-tooltip="Löschen" type="button" @click="del(slotProps.row)">
          <trash-icon class="h-5 w-5" />
        </button>
      </div>
    </template>

  </sortable>

  <alert
      ref="confirm"
      header-class="bg-error text-white"
      :buttons="[
            { label: 'Löschen!', value: true, 'class': 'button alert' },
            { label: 'Abbrechen', value: false, 'class': 'button cancel' }
          ]"
  />
</template>

<script>
export default {
  name: "Articles",
  emits: ['notify'],
  inject: ['api'],
  data() {
    return {
      articles: [],
      categories: [],
      pagination: {
        page: 1,
        entriesPerPage: 20
      },
      cols: [
        { label: "Kategorie", sortable: true, prop: "catId" },
        { label: "Titel", sortable: true, prop: "title" },
        { label: "Pub", sortable: true, prop: "pub" },
        { label: "*", sortable: true, prop: "marked" },
        { label: "Artikeldatum", sortable: true, prop: "date" },
        { label: "Anzeige von", sortable: true, prop: "from" },
        { label: "Anzeige bis", sortable: true, prop: "until" },
        { label: "Sortierziffer", sortable: true, prop: "sort" },
        { label: "Angelegt/aktualisiert", sortable: true, prop: "updated" },
        { label: "", prop: "action" }
      ],
      initSort: {},
      filter: {
        cat: 0,
        title: ''
      },
      entriesPerPage: 20,
      currentPage: 1
    }
  },

  computed: {
    filteredArticles () {
      const titleFilter = this.filter.title.toLowerCase();
      return this.articles.filter(item => (!this.filter.cat || this.filter.cat === item.catId) && (!this.filter.title || item.title.toLowerCase().indexOf(titleFilter) !== -1));
    },
    paginatedArticles () {
      return this.filteredArticles.slice((this.pagination.page - 1) * this.pagination.entriesPerPage, this.pagination.page * this.pagination.entriesPerPage);
    }
  },

  async created () {
    let lsValue = window.localStorage.getItem(window.location.origin + "/admin/articles__sort__");
    if(lsValue) {
      this.initSort = JSON.parse(lsValue);
    }

    let response = await this.$fetch(this.api + "articles");

    this.categories = response.categories || [];

    this.categories.forEach(item => item.key = item.id);
    this.articles = response.articles || [];
  },

  methods: {
    async del (article) {
      if (await this.$refs.confirm.open('Artikel löschen', "'" + article.title + "' wirklich löschen?")) {
        let response = await this.$fetch(this.api + 'article/' + article.id, 'DELETE');
        if (response.success) {
          this.articles.splice(this.articles.findIndex(item => article === item), 1);
        }
        this.$emit('notify', response);
      }

    },
    storeSort () {
    },
    async publish (row) {
      row.pub = !row.pub;
      let response = await this.$fetch(this.api + 'article/' + row.id + (row.pub ? '/publish' : '/unpublish'), 'PUT');
      if(!response.success) {
        row.pub = !row.pub;
      }
      this.$emit('notify', response);
    }
  }
}
</script>
