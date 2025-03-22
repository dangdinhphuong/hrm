import * as XLSX from "xlsx";

export function exportToExcel(data = [], filename = "data", fileType = "xlsx", sheetName = "Sheet1") {
    if (!data.length) {
        console.warn("Không có dữ liệu để xuất!");
        return;
    }

    // Chuyển đổi dữ liệu thành worksheet
    const worksheet = XLSX.utils.aoa_to_sheet(data);

    // Áp dụng style cho header
    const range = XLSX.utils.decode_range(worksheet["!ref"]);
    for (let col = range.s.c; col <= range.e.c; col++) {
        const cellAddress = XLSX.utils.encode_cell({ r: 0, c: col }); // Lấy ô header
        if (worksheet[cellAddress]) {
            worksheet[cellAddress].s = {
                font: { bold: true, sz: 12, color: { rgb: "FFFFFF" } }, // Chữ trắng, đậm, size 12
                alignment: { horizontal: "center", vertical: "center" }, // Căn giữa
                fill: { fgColor: { rgb: "0070C0" } }, // Màu nền xanh dương
            };
        }
    }

    // Tạo workbook và xuất file
    const workbook = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(workbook, worksheet, sheetName);
    XLSX.writeFile(workbook, `${filename}.${fileType}`);
}


export function exportToExcelMultipleSheets(sheetsData = [], filename = "data", fileType = "xlsx") {
    if (!sheetsData.length) {
        console.warn("Không có dữ liệu để xuất!");
        return;
    }

    // Tạo một workbook mới
    const workbook = XLSX.utils.book_new();

    sheetsData.forEach(({ sheetName, data }) => {
        if (!data.length) return; // Bỏ qua sheet trống

        // Chuyển đổi dữ liệu thành worksheet
        const worksheet = XLSX.utils.aoa_to_sheet(data);

        // Áp dụng style cho header
        const range = XLSX.utils.decode_range(worksheet["!ref"]);
        for (let col = range.s.c; col <= range.e.c; col++) {
            const cellAddress = XLSX.utils.encode_cell({ r: 0, c: col }); // Lấy ô header
            if (worksheet[cellAddress]) {
                worksheet[cellAddress].s = {
                    font: { bold: true, sz: 12, color: { rgb: "FFFFFF" } },
                    alignment: { horizontal: "center", vertical: "center" },
                    fill: { fgColor: { rgb: "0070C0" } },
                };
            }
        }

        // Thêm worksheet vào workbook
        XLSX.utils.book_append_sheet(workbook, worksheet, sheetName || `Sheet${workbook.SheetNames.length + 1}`);
    });

    // Lưu file
    XLSX.writeFile(workbook, `${filename}.${fileType}`);
}
