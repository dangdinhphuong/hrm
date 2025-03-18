import {translate} from "@/helpers/CommonHelper.js";

const USER_STATUS_ACTIVE = 1;
const USER_STATUS_DEACTIVATE = 0;
const VIETNAM = 1;
const ENGLAND = 2;

const STATUS_ACTIVE = 1;
const STATUS_DEACTIVATE = 2;

const GENDER_MALE = 1;
const GENDER_FEMALE = 2;
const GENDER_OTHER = 3;

const MARRIAGE_SINGLE = 1;
const MARRIAGE_MARRIED = 2;
const MARRIAGE_DIVORCED = 3;
const MARRIAGE_WIDOWED = 4;
const MARRIAGE_NOT_SPECIFIED = 5;

const LEVEL_DEFAULT = 1;
const CONTRACT_TYPE_DEFAULT = 2;

const LEVEL = {};
for (let i = 1; i <= 10; i++) {
    LEVEL[i] = i;
}



const USER_STATUS = new Map([
    [1, 'Kích hoạt'],
    [2, 'Hủy kích hoạt']
]);
const CALL_CENTER = new Map([
    [1, 'ccsip.educa.vn'],
    [2, 'ccsip02.educa.vn'],
    [3, 'ccgw01.educa.vn'],
    [4, 'ccgw02.educa.vn']
]);

const REGIONS = new Map([
    [1, translate('order.regions.north')],
    [2, translate('order.regions.central_region')],
    [3, translate('order.regions.southern_region')]
]);
const COUNTRY = new Map([
    [ENGLAND, translate('country.England')],
    [VIETNAM, translate('country.VietNam')]
]);

const HRM = {
    GENDER: {
        [GENDER_MALE]: translate('gender.male'),
        [GENDER_FEMALE]: translate('gender.female'),
        [GENDER_OTHER]: translate('gender.other')
    },
    MARRIAGE_STATUS: {
        [MARRIAGE_SINGLE]: translate('marital_status.single'),
        [MARRIAGE_MARRIED]: translate('marital_status.married'),
        [MARRIAGE_DIVORCED]: translate('marital_status.divorced'),
        [MARRIAGE_WIDOWED]: translate('marital_status.widowed'),
        [MARRIAGE_NOT_SPECIFIED]: translate('marital_status.not_specified')
    },
    STATUS: {
        [STATUS_ACTIVE]: translate('status.active'),
        [STATUS_DEACTIVATE]: translate('status.deactivate')
    },
    CONTRACT_TYPE : {
        [1]: translate('hrm.contract.other'),
        [2]: translate('hrm.contract.probationary_contract'),
        [3]: translate('hrm.contract.definite_term_labor_contract'),
        [4]: translate('hrm.contract.indefinite_term_labor_contract'),
    },
    LEVEL

}

const LEAVE_REASONS = new Map([
    [1, translate('requests.columns.annual_leave')], // Nghỉ phép
    [2, translate('requests.columns.unpaid_leave')], // Nghỉ không lương
    [3, translate('requests.columns.missing_checkin')], // Quên chấm công
    [4, translate('requests.columns.business_trip')], // Đi công tác/Làm việc ngoài văn phòng Công ty
    [5, translate('requests.columns.other')] // Lý do khác
]);

const PENDING_APPROVAL = 0;
const APPROVED = 1;
const REJECTED = 2;

const APPROVAL_STATUS = new Map([
    [PENDING_APPROVAL, translate('requests.columns.pending_approval')], // Chờ duyệt
    [APPROVED, translate('requests.columns.approved')], // Đã duyệt
    [REJECTED, translate('requests.columns.rejected')] // Từ chối
]);


export default {
    PENDING_APPROVAL,
    APPROVED,
    REJECTED,
    HRM,
    STATUS_ACTIVE,
    STATUS_DEACTIVATE,
    GENDER_MALE,
    GENDER_FEMALE,
    GENDER_OTHER,
    MARRIAGE_SINGLE,
    MARRIAGE_MARRIED,
    MARRIAGE_DIVORCED,
    MARRIAGE_WIDOWED,
    MARRIAGE_NOT_SPECIFIED,
    LEVEL_DEFAULT,
    USER_STATUS_ACTIVE,
    USER_STATUS_DEACTIVATE,
    USER_STATUS,
    CALL_CENTER,
    REGIONS,
    COUNTRY,
    VIETNAM,
    ENGLAND,
    CONTRACT_TYPE_DEFAULT,
    LEAVE_REASONS,
    APPROVAL_STATUS
};
