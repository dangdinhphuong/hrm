import * as yup from 'yup';
import BaseRequest from "@/requests/BaseRequest.js";
import {translate} from "@/helpers/CommonHelper.js";

const pathTranslate = 'hrm.personal_information.';

const createUserSchema = yup.object().shape({
    first_name: yup.string()
        .required(translate(pathTranslate+'first_name'))
        .max(100, translate(pathTranslate+'first_name')),
    last_name: yup.string()
        .required(translate(pathTranslate+'last_name'))
        .max(100, translate(pathTranslate+'first_name')),
    phone: yup.string()
        .required(translate(pathTranslate+'phone_number'))
        .matches(/^0[0-9]{9}$/, translate(pathTranslate+'phone_number')),
    status: yup.number()
        .required(translate(pathTranslate+'status'))
        .integer(translate(pathTranslate+'status')),
    code: yup.string()
        .required(translate(pathTranslate+'employee_code')),
    identity_card_number: yup.string()
        .required(translate(pathTranslate+'citizen_id_number')),
    identity_card_issue_date: yup.string()
        .required(translate(pathTranslate+'citizen_id_issue_date')),
    identity_card_place: yup.string()
        .required(translate(pathTranslate+'citizen_id_issued_by')),
    nationality: yup.string()
        .required(translate(pathTranslate+'nationality')),
    current_country_id: yup.number()
        .required(translate('country.title'))
        .typeError(translate('country.title'))
        .integer(translate('country.title')),
    current_district_id: yup.number()
        .required(translate('delivery_note.columns.district_id'))
        .typeError(translate('delivery_note.columns.district_id'))
        .integer(translate('delivery_note.columns.district_id')),
    current_province_id: yup.number()
        .required(translate('delivery_note.columns.province_id'))
        .typeError(translate('delivery_note.columns.province_id'))
        .integer(translate('delivery_note.columns.province_id')),
    department_id: yup.string()
        .required(translate(pathTranslate+'department')),
    position_id: yup.string()
        .required(translate(pathTranslate+'job_title')),
    level: yup.number()
        .required(translate(pathTranslate+'current_level')),
});

class CreateUserRequest extends BaseRequest {
    constructor() {
        super(createUserSchema);
    }
}

export default CreateUserRequest;
