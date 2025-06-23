<?php

if (!function_exists('webDesignVisible')) {
    /**
     * Kiểm tra xem một section có được hiển thị không
     *
     * @param string $sectionKey
     * @return bool
     */
    function webDesignVisible(string $sectionKey): bool
    {
        $webDesign = \App\Models\WebDesign::first();
        
        if (!$webDesign) {
            return true; // Mặc định hiển thị nếu chưa có cấu hình
        }

        $enabledField = $sectionKey . '_enabled';
        return $webDesign->$enabledField ?? true;
    }
}

if (!function_exists('webDesignData')) {
    /**
     * Lấy dữ liệu của một section
     *
     * @param string $sectionKey
     * @return \App\Models\WebDesign|null
     */
    function webDesignData(string $sectionKey): ?\App\Models\WebDesign
    {
        return \App\Models\WebDesign::first();
    }
}

if (!function_exists('webDesignContent')) {
    /**
     * Lấy content của một section
     *
     * @param string $sectionKey
     * @param string $field
     * @param mixed $default
     * @return mixed
     */
    function webDesignContent(string $sectionKey, string $field, $default = null)
    {
        $webDesign = \App\Models\WebDesign::first();
        
        if (!$webDesign) {
            return $default;
        }

        // Xử lý đặc biệt cho footer
        if ($sectionKey === 'footer') {
            if ($field === 'policies') {
                $policies = [];

                // Policy 1
                $policy1Url = '#';
                if ($webDesign->footer_policy_1_type === 'post' && $webDesign->footer_policy_1_post) {
                    $policy1Url = route('posts.show', $webDesign->footer_policy_1_post);
                } elseif ($webDesign->footer_policy_1_type === 'custom' && $webDesign->footer_policy_1_url) {
                    $policy1Url = $webDesign->footer_policy_1_url;
                }

                $policies[] = [
                    'title' => $webDesign->footer_policy_1_title ?? 'Chính sách & Điều khoản',
                    'url' => $policy1Url
                ];

                // Policy 2
                $policy2Url = '#';
                if ($webDesign->footer_policy_2_type === 'post' && $webDesign->footer_policy_2_post) {
                    $policy2Url = route('posts.show', $webDesign->footer_policy_2_post);
                } elseif ($webDesign->footer_policy_2_type === 'custom' && $webDesign->footer_policy_2_url) {
                    $policy2Url = $webDesign->footer_policy_2_url;
                }

                $policies[] = [
                    'title' => $webDesign->footer_policy_2_title ?? 'Hệ thống đại lý',
                    'url' => $policy2Url
                ];

                // Policy 3
                $policy3Url = '#';
                if ($webDesign->footer_policy_3_type === 'post' && $webDesign->footer_policy_3_post) {
                    $policy3Url = route('posts.show', $webDesign->footer_policy_3_post);
                } elseif ($webDesign->footer_policy_3_type === 'custom' && $webDesign->footer_policy_3_url) {
                    $policy3Url = $webDesign->footer_policy_3_url;
                }

                $policies[] = [
                    'title' => $webDesign->footer_policy_3_title ?? 'Bảo mật & Quyền riêng tư',
                    'url' => $policy3Url
                ];

                return $policies;
            }
            
            if ($field === 'copyright') {
                return $webDesign->footer_copyright ?? '© ' . date('Y') . ' Copyright by VBA Vũ Phúc - All Rights Reserved';
            }

            // Company info fields
            if ($field === 'company_brand_name') {
                return $webDesign->footer_company_brand_name ?? 'VUPHUC BAKING®';
            }

            if ($field === 'company_business_license') {
                return $webDesign->footer_company_business_license ?? 'Giấy phép kinh doanh số 1800935879 cấp ngày 29/4/2009';
            }

            if ($field === 'company_director_info') {
                return $webDesign->footer_company_director_info ?? 'Chịu trách nhiệm nội dung: Trần Uy Vũ - Tổng Giám đốc';
            }
        }

        // Xử lý các field thông thường
        $fieldName = $sectionKey . '_' . $field;
        return $webDesign->$fieldName ?? $default;
    }
}

if (!function_exists('webDesignSettings')) {
    /**
     * Lấy cấu hình settings của một section
     *
     * @param string $sectionKey
     * @param string|null $key
     * @param mixed $default
     * @return mixed
     */
    function webDesignSettings(string $sectionKey, ?string $key = null, $default = null)
    {
        $webDesign = \App\Models\WebDesign::first();
        
        if (!$webDesign) {
            return $default;
        }

        // Trả về toàn bộ settings nếu không có key cụ thể
        if ($key === null) {
            return $webDesign->settings ?? [];
        }

        $settings = $webDesign->settings ?? [];
        return data_get($settings, $key, $default);
    }
}

if (!function_exists('getAllWebDesignSections')) {
    /**
     * Lấy tất cả sections theo thứ tự
     *
     * @return array
     */
    function getAllWebDesignSections(): array
    {
        $webDesign = \App\Models\WebDesign::first();
        
        if (!$webDesign) {
            return [];
        }

        return $webDesign->getOrderedSections();
    }
}
