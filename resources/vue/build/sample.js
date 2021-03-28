import Autocomplete from '../components/vx-vue/autocomplete';
import DatePicker from '../components/vx-vue/formelements/datepicker';
import Confirm from '../components/vx-vue/confirm';
import Alert from '../components/vx-vue/alert';
import PasswordInput from '../components/vx-vue/formelements/password-input';
import Pagination from '../components/vx-vue/pagination';
import FormSelect from '../components/vx-vue/formelements/form-select';
import FormSwitch from '../components/vx-vue/formelements/form-switch';
import FormFileButton from '../components/vx-vue/formelements/form-file-button';

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
    DatePicker,
    Confirm,
    Alert,
    Pagination
};

const Directives = {
    Focus,
    Bubble
};

const Util = {
    SimpleFetch,
    PromisedXhr,
    UrlQuery,
    DateFunctions,
    BytesToSize
};

export { Components, Directives, Util };