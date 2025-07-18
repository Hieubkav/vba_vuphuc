<?php

return [
    'success' => [
        'created' => ':name đã được tạo thành công.',
        'updated' => ':name đã được cập nhật thành công.',
        'deleted' => ':name đã được xóa thành công.',
        'restored' => ':name đã được khôi phục thành công.',
        'saved' => 'Đã lưu thành công.',
        'uploaded' => 'Tải lên thành công.',
        'imported' => 'Nhập dữ liệu thành công.',
        'exported' => 'Xuất dữ liệu thành công.',
    ],

    'error' => [
        'not_found' => 'Không tìm thấy :name.',
        'unauthorized' => 'Bạn không có quyền thực hiện hành động này.',
        'forbidden' => 'Truy cập bị từ chối.',
        'validation_failed' => 'Dữ liệu không hợp lệ.',
        'server_error' => 'Lỗi máy chủ. Vui lòng thử lại sau.',
        'file_upload_failed' => 'Tải lên tệp thất bại.',
        'file_too_large' => 'Tệp quá lớn.',
        'invalid_file_type' => 'Loại tệp không được hỗ trợ.',
    ],

    'warning' => [
        'unsaved_changes' => 'Bạn có thay đổi chưa được lưu.',
        'confirm_delete' => 'Bạn có chắc chắn muốn xóa :name?',
        'confirm_action' => 'Bạn có chắc chắn muốn thực hiện hành động này?',
        'irreversible_action' => 'Hành động này không thể hoàn tác.',
    ],

    'info' => [
        'loading' => 'Đang tải...',
        'processing' => 'Đang xử lý...',
        'saving' => 'Đang lưu...',
        'uploading' => 'Đang tải lên...',
        'no_data' => 'Không có dữ liệu.',
        'empty_list' => 'Danh sách trống.',
        'search_no_results' => 'Không tìm thấy kết quả nào.',
    ],

    'navigation' => [
        'dashboard' => 'Bảng điều khiển',
        'content_management' => 'Quản lý nội dung',
        'course_management' => 'Quản lý khóa học',
        'user_management' => 'Quản lý người dùng',
        'system' => 'Hệ thống',
        'settings' => 'Cài đặt',
        'reports' => 'Báo cáo',
        'tools' => 'Công cụ',
    ],

    'models' => [
        'post' => 'bài viết',
        'posts' => 'bài viết',
        'course' => 'khóa học',
        'courses' => 'khóa học',
        'category' => 'danh mục',
        'categories' => 'danh mục',
        'user' => 'người dùng',
        'users' => 'người dùng',
        'instructor' => 'giảng viên',
        'instructors' => 'giảng viên',
        'student' => 'học viên',
        'students' => 'học viên',
        'album' => 'album',
        'albums' => 'album',
        'partner' => 'đối tác',
        'partners' => 'đối tác',
        'testimonial' => 'lời khen',
        'testimonials' => 'lời khen khách hàng',
        'faq' => 'câu hỏi thường gặp',
        'faqs' => 'câu hỏi thường gặp',
        'slider' => 'slider',
        'sliders' => 'slider',
        'menu' => 'menu',
        'menus' => 'menu',
        'association' => 'hiệp hội',
        'associations' => 'hiệp hội',
        'course_group' => 'nhóm khóa học',
        'course_groups' => 'nhóm khóa học',
        'course_material' => 'tài liệu khóa học',
        'course_materials' => 'tài liệu khóa học',
    ],

    'status' => [
        'active' => 'Hoạt động',
        'inactive' => 'Không hoạt động',
        'draft' => 'Nháp',
        'published' => 'Đã xuất bản',
        'pending' => 'Chờ duyệt',
        'approved' => 'Đã duyệt',
        'rejected' => 'Bị từ chối',
        'archived' => 'Lưu trữ',
        'deleted' => 'Đã xóa',
    ],

    'actions' => [
        'create' => 'Tạo mới',
        'edit' => 'Chỉnh sửa',
        'update' => 'Cập nhật',
        'delete' => 'Xóa',
        'restore' => 'Khôi phục',
        'view' => 'Xem',
        'show' => 'Hiển thị',
        'hide' => 'Ẩn',
        'save' => 'Lưu',
        'cancel' => 'Hủy',
        'submit' => 'Gửi',
        'reset' => 'Đặt lại',
        'search' => 'Tìm kiếm',
        'filter' => 'Lọc',
        'sort' => 'Sắp xếp',
        'export' => 'Xuất',
        'import' => 'Nhập',
        'upload' => 'Tải lên',
        'download' => 'Tải xuống',
        'copy' => 'Sao chép',
        'duplicate' => 'Nhân bản',
        'move' => 'Di chuyển',
        'archive' => 'Lưu trữ',
        'publish' => 'Xuất bản',
        'unpublish' => 'Hủy xuất bản',
        'approve' => 'Duyệt',
        'reject' => 'Từ chối',
        'enable' => 'Bật',
        'disable' => 'Tắt',
        'activate' => 'Kích hoạt',
        'deactivate' => 'Vô hiệu hóa',
        'refresh' => 'Làm mới',
        'reload' => 'Tải lại',
        'back' => 'Quay lại',
        'next' => 'Tiếp theo',
        'previous' => 'Trước đó',
        'close' => 'Đóng',
        'open' => 'Mở',
        'expand' => 'Mở rộng',
        'collapse' => 'Thu gọn',
        'select_all' => 'Chọn tất cả',
        'deselect_all' => 'Bỏ chọn tất cả',
        'bulk_actions' => 'Thao tác hàng loạt',
        'bulk_delete' => 'Xóa hàng loạt',
        'bulk_update' => 'Cập nhật hàng loạt',
    ],

    'fields' => [
        'id' => 'ID',
        'name' => 'Tên',
        'title' => 'Tiêu đề',
        'description' => 'Mô tả',
        'content' => 'Nội dung',
        'slug' => 'Đường dẫn',
        'status' => 'Trạng thái',
        'order' => 'Thứ tự',
        'created_at' => 'Ngày tạo',
        'updated_at' => 'Ngày cập nhật',
        'published_at' => 'Ngày xuất bản',
        'image' => 'Hình ảnh',
        'thumbnail' => 'Hình đại diện',
        'featured_image' => 'Hình nổi bật',
        'category' => 'Danh mục',
        'tags' => 'Thẻ',
        'author' => 'Tác giả',
        'email' => 'Email',
        'phone' => 'Số điện thoại',
        'address' => 'Địa chỉ',
        'website' => 'Website',
        'price' => 'Giá',
        'discount' => 'Giảm giá',
        'quantity' => 'Số lượng',
        'stock' => 'Tồn kho',
        'sku' => 'Mã sản phẩm',
        'weight' => 'Trọng lượng',
        'dimensions' => 'Kích thước',
        'color' => 'Màu sắc',
        'size' => 'Kích cỡ',
        'brand' => 'Thương hiệu',
        'model' => 'Mẫu',
        'type' => 'Loại',
        'level' => 'Cấp độ',
        'duration' => 'Thời lượng',
        'start_date' => 'Ngày bắt đầu',
        'end_date' => 'Ngày kết thúc',
        'instructor' => 'Giảng viên',
        'students' => 'Học viên',
        'max_students' => 'Số học viên tối đa',
        'requirements' => 'Yêu cầu',
        'what_you_learn' => 'Những gì học được',
        'seo_title' => 'Tiêu đề SEO',
        'seo_description' => 'Mô tả SEO',
        'meta_keywords' => 'Từ khóa meta',
        'og_image' => 'Hình ảnh OG',
        'is_featured' => 'Nổi bật',
        'is_published' => 'Đã xuất bản',
        'is_active' => 'Hoạt động',
        'show_on_homepage' => 'Hiển thị trang chủ',
        'allow_comments' => 'Cho phép bình luận',
        'password' => 'Mật khẩu',
        'password_confirmation' => 'Xác nhận mật khẩu',
        'role' => 'Vai trò',
        'permissions' => 'Quyền',
        'last_login' => 'Đăng nhập cuối',
        'email_verified_at' => 'Email đã xác thực',
        'remember_token' => 'Token ghi nhớ',
    ],

    'time' => [
        'just_now' => 'Vừa xong',
        'minutes_ago' => ':count phút trước',
        'hours_ago' => ':count giờ trước',
        'days_ago' => ':count ngày trước',
        'weeks_ago' => ':count tuần trước',
        'months_ago' => ':count tháng trước',
        'years_ago' => ':count năm trước',
    ],
];