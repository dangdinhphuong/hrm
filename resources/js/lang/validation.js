// validators/validation.js
const messages = {
    required: 'Trường :attribute là bắt buộc.',
    required_array_keys: 'Trường :attribute phải chứa mục nhập cho: :values.',
    required_if: 'Trường :attribute là bắt buộc khi :other là :value.',
    required_if_accepted: 'Trường :attribute là bắt buộc khi :other được chấp nhận.',
    required_unless: 'Trường :attribute là bắt buộc trừ khi :other là một trong :values.',
    required_with: 'Trường :attribute là bắt buộc khi :values có mặt.',
    required_with_all: 'Trường :attribute là bắt buộc khi :values có mặt.',
    required_without: 'Trường :attribute là bắt buộc khi :values không có mặt.',
    required_without_all: 'Trường :attribute là bắt buộc khi không có một trong :values có mặt.',
    nullable: 'Trường :attribute yêu cầu nhập .',
    // Các thông báo lỗi khác
    max: 'Trường :attribute không thể dài hơn :max ký tự.',
    integer: 'Trường :attribute phải là một số nguyên.',
    matches: 'Trường :attribute không hợp lệ.',
    // Thêm các thông báo khác nếu cần
};

export default messages;
