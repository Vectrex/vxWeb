import Autocomplete from '../components/vx-vue/autocomplete';
import Datepicker from '../components/vx-vue/formelements/datepicker';
import Confirm from '../components/vx-vue/confirm';
import Alert from '../components/vx-vue/alert';
import MessageToast from '../components/vx-vue/message-toast';
import PasswordInput from '../components/vx-vue/formelements/password-input';
import Pagination from '../components/vx-vue/pagination';
import FormSelect from '../components/vx-vue/formelements/form-select';
import FormSwitch from '../components/vx-vue/formelements/form-switch';
import FormFileButton from '../components/vx-vue/formelements/form-file-button';
import Sortable from '../components/vx-vue/sortable';
import CKEditor from '../components/vue-ckeditor';

import SimpleFetch from '../util/simple-fetch';
import PromisedXhr from '../util/promised-xhr';
import UrlQuery from '../util/url-query';
import DateFunctions from '../util/date-functions';
import BytesToSize from '../util/bytes-to-size';

import { Focus, Bubble } from '../directives';

const Components = {
    FormSelect,
    FormSwitch,
    FormFileButton,
    PasswordInput,
    Autocomplete,
    Datepicker,
    Confirm,
    Alert,
    MessageToast,
    Pagination,
    Sortable,
    CKEditor
};

const Directives = {
    Focus,
    Bubble
};

const Plugins = {
};

const Util = {
    SimpleFetch,
    PromisedXhr,
    UrlQuery,
    DateFunctions,
    BytesToSize
};

export { Components, Directives, Util, Plugins };