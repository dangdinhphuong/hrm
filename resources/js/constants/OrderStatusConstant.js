import {translate} from "@/helpers/CommonHelper.js";
const STATUS_NOT_YET_SHIPPED = 0;
const STATUS_CLOSE_APPLICATION = 8;
const STATUS_DELIVERED_SUCCESSFULLY = 1;
const STATUS_DELIVERING = 2;
const STATUS_COMPLETED = 4;
const STATUS_RETURN = 5;
const STATUS_CANCEL = 7;
const STATUS_RETRIBUTION = 9;
const STATUS_OVERDUE_FOR_PROCESSING = 10;
const STATUS_WAITING_FOR_SALES_TO_PROCESS = 11;
const STATUS_DHL_DELIVERY = 101;
const STATUS_RECEPTION = 12;
const STATUS_SALE_CANCELED = 13;

// Define constant variables for numeric keys in ORDER_PAYMENT_METHOD
const ORDER_PAYMENT_METHOD_DIRECT_ID = 1;
const ORDER_PAYMENT_METHOD_TRANSFER_ID = 2;
const ORDER_PAYMENT_METHOD_INSTALLMENTS_ID = 3;

// Define constant variables for numeric keys in SHIPPING_CARRIER
const SHIPPING_CARRIER_OTHER_ID = 1;
const SHIPPING_CARRIER_VIETTEL_POST_ID = 2;
const SHIPPING_CARRIER_EMS_ID = 3;
const SHIPPING_CARRIER_DHL_ID = 4;
const SHIPPING_CARRIER_SUPER_SHIP_ID = 5;

const ORDER_STATUS = {
    UNPAID: "login",
    PAID: "home-page",
    ARE_BEING_PAID: "dashboard",
    PAYMENT_STATUS: {
        0: translate('order.payment_status_type.unpaid'),
        1: translate('order.payment_status_type.paid'),
        2: translate('order.payment_status_type.are_being_paid'),
    },
    REGIONS: {
        1: translate('order.regions.north'),
        2: translate('order.regions.central_region'),
        3: translate('order.regions.southern_region'),
    },
    ORDER_FOT: {
        1: translate('order.fot.delivery_unit'),
        2: translate('order.fot.ship_now'),
        3: translate('order.fot.switch_to_province'),
        4: translate('order.fot.soft_Code_Conversion'),
        5: translate('order.fot.school'),
    },
    ORDER_PAYMENT_METHOD: {
        1: translate('order.payment_methods.direct_payment'),
        2: translate('order.payment_methods.transfer_payment'),
        3: translate('order.payment_methods.pay_in_installments'),
    },
    TYPE: {
        NEW: translate('order.type.new'),
        EXTEND: translate('order.type.extend'),
        UPSALE: translate('order.type.upsale'),
        CROSS_SALE: translate('order.type.cross_sale'),
    },
    STATUS: {
        0: translate('order.status_list.not_yet_shipped'),
        8: translate('order.status_list.close_application'),
        1: translate('order.status_list.delivered_successfully'),
        2: translate('order.status_list.delivering'),
        4: translate('order.status_list.completed'),
        5: translate('order.status_list.return'),
        7: translate('order.status_list.cancel'),
        9: translate('order.status_list.retribution'),
        10: translate('order.status_list.overdue_for_processing'),
        11: translate('order.status_list.waiting_for_sales_to_process'),
        101: translate('order.status_list.dhl_delivery'),
        12: translate('order.status_list.reception'),
        13: translate('order.status_list.sale_canceled'),
    },
    SHIPPING_CARRIER: {
        1: "other",
        2: "viettel_post",
        3: "ems",
        4: "dhl",
        5: "super_ship",
    },
};


export default {
    ORDER_STATUS,
    STATUS_NOT_YET_SHIPPED,
    ORDER_PAYMENT_METHOD_DIRECT_ID,
    SHIPPING_CARRIER_OTHER_ID,
};
