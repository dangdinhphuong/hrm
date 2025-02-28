import messages from '@/lang/validation.js';
import {translate} from "@/helpers/CommonHelper.js";

class BaseRequest {
    constructor(schema, customMessages = {}) {
        this.schema = schema;
        this.messages = {...messages, ...customMessages};
    }

    getMessage(template, replacements) {
        return template.replace(/:([a-z_]+)/g, (_, key) => replacements[key] || key);
    }

    async validate(data) {
        try {
            await this.schema.validate(data, {abortEarly: false});
            return {isValid: true, errorMessages: {}};
        } catch (validationErrors) {
            const errorMessages = validationErrors.inner.reduce((acc, err) => {
                const replacements = {
                    attribute: err.message.toLowerCase(),
                    max: err.params.max,
                    value: err.params.value,
                    values: err.params.values,
                    other: err.params.other,
                };
                const messageTemplate = this.messages[err.type] || err.message;
                acc[err.path] = this.getMessage(messageTemplate, replacements);
                return acc;
            }, {});
            return {isValid: false, errorMessages};
        }
    }
}

export default BaseRequest;

