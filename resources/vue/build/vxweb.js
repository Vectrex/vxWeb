import MessageToast from '../components/vx-vue/message-toast.vue';
import CircularProgress from '../components/circular-progress.vue';
import Autocomplete from '../components/vx-vue/autocomplete.vue';
import DatePicker from '../components/vx-vue/formelements/datepicker.vue';
import Sortable from '../components/vx-vue/sortable.vue';
import SimpleTree from '../components/vx-vue/simple-tree/simple-tree.vue';
import Filemanager from '../components/filemanager/filemanager.vue';
import Tab from '../components/vx-vue/tab.vue';
import Confirm from '../components/vx-vue/confirm.vue';
import Alert from '../components/vx-vue/alert.vue';
import PasswordInput from '../components/vx-vue/formelements/password-input.vue';
import CookieConsent from '../components/cookie-consent.vue';

import Pagination from '../components/vx-vue/pagination.vue';
import VueCkeditor from "../components/vue-ckeditor.vue";

import SimpleFetch from '../util/simple-fetch';
import PromisedXhr from '../util/promised-xhr';
import UrlQuery from '../util/url-query';
import DateFunctions from '../util/date-functions';

import ProfileForm from '../components/forms/profile-form.vue';
import UserForm from '../components/forms/user-form.vue';
import ArticleForm from '../components/forms/article-form.vue';
import PageForm from '../components/forms/page-form.vue';

import { formatFilesize } from '../filters';
import { Focus } from '../directives';

import { SlickList, SlickItem, plugin as Slicksort, HandleDirective as Handle } from 'vue-slicksort';

const Components = {
    MessageToast,
    CircularProgress,
    Autocomplete,
    DatePicker,
    Sortable,
    SimpleTree,
    Filemanager,
    SlickList, SlickItem,
    Tab,
    Confirm,
    Alert,
    PasswordInput,
    CookieConsent,
    Pagination,
    VueCkeditor,
    ProfileForm,
    UserForm,
    ArticleForm,
    PageForm
};

const Filters = {
    formatFilesize
};

const Directives = {
    Focus,
    Handle
};

const Util = {
    SimpleFetch,
    PromisedXhr,
    UrlQuery,
    DateFunctions
};

const Plugins = {
    Slicksort
};

export { Components, Filters, Directives, Util, Plugins };