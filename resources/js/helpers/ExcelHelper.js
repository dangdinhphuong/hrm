import * as XLSX from 'xlsx';
export function exportToExcel ()  {
    console.log('Exporting to Excel...');
    // Sample data
    const data = [
        { name: 'John Doe', age: 28, email: 'john@example.com' },
        { name: 'Jane Doe', age: 22, email: 'jane@example.com' }
    ];

    // Convert JSON to worksheet
    const worksheet = XLSX.utils.json_to_sheet(data);

    // Create a new workbook
    const workbook = XLSX.utils.book_new();

    // Append the worksheet to the workbook
    XLSX.utils.book_append_sheet(workbook, worksheet, 'Sheet1');

    // Generate Excel file and trigger download
    XLSX.writeFile(workbook, 'data.xlsx');
};
