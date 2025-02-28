<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class FileValidationRule implements Rule
{
    protected $errorType;
    protected $fileName; // Variable to store file name
    protected $totalSize; // Variable to track total size of uploaded files
    protected $maxTotalSize; // Maximum total size of all files in KB
    protected $maxFileSize; // Maximum size of each individual file in KB

    /**
     * Create a new rule instance with dynamic max file size and total size.
     */
    public function __construct($maxTotalSize = 7 * 1024, $maxFileSize = 2 * 1024) // Defaults: 7MB total, 2MB per file
    {
        $this->errorType = '';
        $this->fileName = '';
        $this->totalSize = 0; // Initialize total size
        $this->maxTotalSize = $maxTotalSize; // Set the max total size
        $this->maxFileSize = $maxFileSize; // Set the max file size
    }

    /**
     * Determine if the validation rule passes.
     */
    public function passes($attribute, $value)
    {
        if (!is_a($value, \Illuminate\Http\UploadedFile::class) || !$value->isValid()) {
            // If it's not a valid file, return true
            return true;
        }

        // Store the file name
        $this->fileName = $value->getClientOriginalName();

        // Get file extension and size
        $extension = $value->getClientOriginalExtension();
        $size = $value->getSize() / 1024; // Convert size to KB

        // Allowed file extensions
        $allowedExtensions = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'txt', 'jpg', 'jpeg', 'png'];

        // Validate file extension
        if (!in_array($extension, $allowedExtensions)) {
            $this->errorType = 'invalid_extension';
            return false;
        }

        // Validate individual file size (must be <= maxFileSize)
        if ($size > $this->maxFileSize) {
            $this->errorType = 'file_too_large';
            return false;
        }

        // Accumulate total file size
        $this->totalSize += $size;

        // Validate total size of all files (must be <= maxTotalSize)
        if ($this->totalSize > $this->maxTotalSize) {
            $this->errorType = 'total_size_exceeded';
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     */
    public function message()
    {
        // Return an error message based on the failure type and file name
        switch ($this->errorType) {
            case 'invalid_extension':
                return 'Tệp "' . $this->fileName . '" phải có một trong các định dạng: pdf, doc, docx, xls, xlsx, txt, jpg, jpeg, png.';
            case 'file_too_large':
                return 'Dung lượng tệp "' . $this->fileName . '" phải nhỏ hơn ' . ($this->maxFileSize / 1024) . 'MB.';
            case 'total_size_exceeded':
                return 'Tổng dung lượng các tệp tải lên không được vượt quá ' . ($this->maxTotalSize / 1024) . 'MB.';
            default:
                return 'Tệp "' . $this->fileName . '" không hợp lệ.';
        }
    }
}
