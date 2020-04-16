import MessageToast from './components/message-toast';
import CircularProgress from './components/circular-progress';
import Autocomplete from './components/autocomplete';
import DatePicker from './components/datepicker';
import Sortable from './components/sortable';
import SimpleTree from './components/simple-tree';
import Filemanager from './components/filemanager';
import SlicksortList from './components/slicksort-list';
import Tab from './components/tab';
import Confirm from './components/confirm';
import Alert from './components/alert';
import PasswordInput from './components/password-input';
import CookieConsent from './components/cookie-consent';

import ZPagination from './components/zutre/pagination';
import ZLink from './components/zutre/link';
import VueCkeditor from "./components/VueCkeditor";

import SimpleFetch from './util/simple-fetch';
import PromisedXhr from './util/promised-xhr';
import UrlQuery from './util/url-query';
import DateFunctions from './util/date-functions';

import ProfileForm from './components/forms/profile-form';
import UserForm from './components/forms/user-form';
import ArticleForm from './components/forms/article-form';
import PageForm from  './components/forms/page-form';

import { formatFilesize } from './filters';
import { Focus, Bubble } from './directives';

import { ContainerMixin, ElementMixin, HandleDirective } from 'vue-slicksort';

const Components = {
    MessageToast,
    CircularProgress,
    Autocomplete,
    DatePicker,
    Sortable,
    SimpleTree,
    Filemanager,
    SlicksortList,
    Tab,
    Confirm,
    Alert,
    PasswordInput,
    CookieConsent,
    ZPagination,
    ZLink,
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
    Bubble,
    HandleDirective
};

const Mixins = {
    ContainerMixin,
    ElementMixin
};

const Util = {
    SimpleFetch,
    PromisedXhr,
    UrlQuery,
    DateFunctions
};

export { Components, Filters, Directives, Mixins, Util };