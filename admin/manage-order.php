<?php include('partials/menu.php'); ?>

<div class="main-content">
    <div class="wrapper">
        <h1>Manage Order</h1>

        <!-- PDF Print Button -->
        <div style="margin: 20px 0;">
            <button onclick="printOrdersPDF()" class="btn-primary" style="background-color: #ff6b81; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 14px;">
                <i class="fas fa-file-pdf"></i> Print Orders PDF
            </button>
        </div>

        <br /><br /><br />

        <?php 
            if(isset($_SESSION['update']))
            {
                echo $_SESSION['update'];
                unset($_SESSION['update']);
            }
        ?>
        <br><br>

        <table class="tbl-full" id="ordersTable">
            <tr>
                <th>S.N.</th>
                <th>Food</th>
                <th>Price</th>
                <th>Qty.</th>
                <th>Total</th>
                <th>Order Date</th>
                <th>Status</th>
                <th>Customer Name</th>
                <th>Contact</th>
                <th>Email</th>
                <th>Address</th>
                <th>Actions</th>
            </tr>

            <?php 
                //Get all the orders from database
                $sql = "SELECT * FROM tbl_order ORDER BY id DESC"; // DIsplay the Latest Order at First
                //Execute Query
                $res = mysqli_query($conn, $sql);
                //Count the Rows
                $count = mysqli_num_rows($res);

                $sn = 1; //Create a Serial Number and set its initail value as 1

                if($count>0)
                {
                    //Order Available
                    while($row=mysqli_fetch_assoc($res))
                    {
                        //Get all the order details
                        $id = $row['id'];
                        $food = $row['food'];
                        $price = $row['price'];
                        $qty = $row['qty'];
                        $total = $row['total'];
                        $order_date = $row['order_date'];
                        $status = $row['status'];
                        $customer_name = $row['customer_name'];
                        $customer_contact = $row['customer_contact'];
                        $customer_email = $row['customer_email'];
                        $customer_address = $row['customer_address'];
                        
                        ?>

                            <tr id="order-row-<?php echo $id; ?>">
                                <td><?php echo $sn++; ?>. </td>
                                <td><?php echo $food; ?></td>
                                <td><?php echo $price; ?></td>
                                <td><?php echo $qty; ?></td>
                                <td><?php echo $total; ?></td>
                                <td><?php echo $order_date; ?></td>

                                <td>
                                    <?php 
                                        // Ordered, On Delivery, Delivered, Cancelled

                                        if($status=="Ordered")
                                        {
                                            echo "<label>$status</label>";
                                        }
                                        elseif($status=="On Delivery")
                                        {
                                            echo "<label style='color: orange;'>$status</label>";
                                        }
                                        elseif($status=="Delivered")
                                        {
                                            echo "<label style='color: green;'>$status</label>";
                                        }
                                        elseif($status=="Cancelled")
                                        {
                                            echo "<label style='color: red;'>$status</label>";
                                        }
                                    ?>
                                </td>

                                <td><?php echo $customer_name; ?></td>
                                <td><?php echo $customer_contact; ?></td>
                                <td><?php echo $customer_email; ?></td>
                                <td><?php echo $customer_address; ?></td>
                                <td>
                                    <a href="<?php echo SITEURL; ?>admin/update-order.php?id=<?php echo $id; ?>" class="btn-secondary">Update Order</a>
                                    <button onclick="printSingleOrderPDF(<?php echo $id; ?>)" class="btn-pdf" style="background-color: #28a745; color: white; padding: 8px 12px; border: none; border-radius: 3px; cursor: pointer; font-size: 12px; margin-left: 5px;">
                                        <i class="fas fa-file-pdf"></i> PDF
                                    </button>
                                </td>
                            </tr>

                        <?php

                    }
                }
                else
                {
                    //Order not Available
                    echo "<tr><td colspan='12' class='error'>Orders not Available</td></tr>";
                }
            ?>

        </table>
    </div>
</div>

<!-- Include jsPDF library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.25/jspdf.plugin.autotable.min.js"></script>

<script>
function printOrdersPDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF('l', 'mm', 'a4'); // landscape orientation
    
    // Add title
    doc.setFontSize(20);
    doc.setFont('helvetica', 'bold');
    doc.text('Order Management Report', 14, 22);
    
    // Add date
    doc.setFontSize(10);
    doc.setFont('helvetica', 'normal');
    const currentDate = new Date().toLocaleDateString();
    doc.text('Generated on: ' + currentDate, 14, 30);
    
    // Get table data
    const table = document.getElementById('ordersTable');
    const rows = table.querySelectorAll('tr');
    
    // Extract headers (excluding Actions column)
    const headers = [];
    const headerCells = rows[0].querySelectorAll('th');
    for (let i = 0; i < headerCells.length - 1; i++) { // -1 to exclude Actions column
        headers.push(headerCells[i].textContent.trim());
    }
    
    // Extract data rows
    const data = [];
    for (let i = 1; i < rows.length; i++) {
        const row = rows[i];
        const cells = row.querySelectorAll('td');
        
        if (cells.length > 0) {
            const rowData = [];
            for (let j = 0; j < cells.length - 1; j++) { // -1 to exclude Actions column
                let cellText = cells[j].textContent.trim();
                
                // Handle status column formatting
                if (j === 6) { // Status column
                    cellText = cellText.replace(/\s+/g, ' ');
                }
                
                // Truncate long addresses
                if (j === 10 && cellText.length > 30) { // Address column
                    cellText = cellText.substring(0, 30) + '...';
                }
                
                rowData.push(cellText);
            }
            data.push(rowData);
        }
    }
    
    // Create the table
    doc.autoTable({
        head: [headers],
        body: data,
        startY: 40,
        styles: {
            fontSize: 8,
            cellPadding: 2,
            overflow: 'linebreak',
            halign: 'left'
        },
        headStyles: {
            fillColor: [255, 107, 129], // Pink color similar to button
            textColor: [255, 255, 255],
            fontSize: 9,
            fontStyle: 'bold'
        },
        columnStyles: {
            0: { halign: 'center', cellWidth: 15 }, // S.N.
            1: { cellWidth: 25 }, // Food
            2: { halign: 'right', cellWidth: 20 }, // Price
            3: { halign: 'center', cellWidth: 15 }, // Qty
            4: { halign: 'right', cellWidth: 20 }, // Total
            5: { cellWidth: 25 }, // Order Date
            6: { halign: 'center', cellWidth: 20 }, // Status
            7: { cellWidth: 30 }, // Customer Name
            8: { cellWidth: 25 }, // Contact
            9: { cellWidth: 35 }, // Email
            10: { cellWidth: 40 } // Address
        },
        alternateRowStyles: {
            fillColor: [248, 248, 248]
        },
        margin: { top: 40, right: 14, bottom: 20, left: 14 },
        tableWidth: 'auto',
        showHead: 'everyPage'
    });
    
    // Add footer
    const pageCount = doc.internal.getNumberOfPages();
    for (let i = 1; i <= pageCount; i++) {
        doc.setPage(i);
        doc.setFontSize(8);
        doc.setFont('helvetica', 'normal');
       doc.text('Page ' + (i + 1) + ' of ' + pageCount, doc.internal.pageSize.width - 50, doc.internal.pageSize.height - 10);

    }
    
    // Save the PDF
    const fileName = 'Orders_Report_' + new Date().toISOString().split('T')[0] + '.pdf';
    doc.save(fileName);
}

// NEW FUNCTION: Print single order PDF
function printSingleOrderPDF(orderId) {
    try {
        // Check if jsPDF is loaded
        if (typeof window.jspdf === 'undefined') {
            alert('PDF library not loaded. Please refresh the page and try again.');
            return;
        }
        
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('p', 'mm', 'a4'); // portrait orientation for single order
        
        // Find the specific order row
        const orderRow = document.getElementById('order-row-' + orderId);
        if (!orderRow) {
            alert('Order not found!');
            return;
        }
        
        const cells = orderRow.querySelectorAll('td');
        if (cells.length < 11) {
            alert('Order data incomplete!');
            return;
        }
        
        // Extract order data with null checks
        const orderData = {
            sn: cells[0] ? cells[0].textContent.trim() : 'N/A',
            food: cells[1] ? cells[1].textContent.trim() : 'N/A',
            price: cells[2] ? cells[2].textContent.trim() : 'N/A',
            qty: cells[3] ? cells[3].textContent.trim() : 'N/A',
            total: cells[4] ? cells[4].textContent.trim() : 'N/A',
            orderDate: cells[5] ? cells[5].textContent.trim() : 'N/A',
            status: cells[6] ? cells[6].textContent.trim() : 'N/A',
            customerName: cells[7] ? cells[7].textContent.trim() : 'N/A',
            contact: cells[8] ? cells[8].textContent.trim() : 'N/A',
            email: cells[9] ? cells[9].textContent.trim() : 'N/A',
            address: cells[10] ? cells[10].textContent.trim() : 'N/A'
        };
        
        // Add header
        doc.setFontSize(20);
        doc.setFont('helvetica', 'bold');
        doc.text('Order Receipt', 105, 20, null, null, 'center');
        
        // Add date
        doc.setFontSize(10);
        doc.setFont('helvetica', 'normal');
        const currentDate = new Date().toLocaleDateString();
        doc.text('Generated on: ' + currentDate, 14, 30);
        
        // Add order ID
        doc.setFontSize(12);
        doc.setFont('helvetica', 'bold');
        doc.text('Order ID: #' + orderId, 14, 40);
        
        // Add a simple line instead of autoTable for better compatibility
        doc.setFontSize(11);
        doc.setFont('helvetica', 'bold');
        doc.text('ORDER DETAILS:', 14, 60);
        
        doc.setFont('helvetica', 'normal');
        let yPos = 70;
        const lineHeight = 8;
        
        doc.text('Food Item: ' + orderData.food, 14, yPos);
        yPos += lineHeight;
        doc.text('Price: ' + orderData.price, 14, yPos);
        yPos += lineHeight;
        doc.text('Quantity: ' + orderData.qty, 14, yPos);
        yPos += lineHeight;
        doc.text('Total Amount: ' + orderData.total, 14, yPos);
        yPos += lineHeight;
        doc.text('Order Date: ' + orderData.orderDate, 14, yPos);
        yPos += lineHeight;
        doc.text('Status: ' + orderData.status, 14, yPos);
        yPos += lineHeight * 2;
        
        // Add customer information
        doc.setFont('helvetica', 'bold');
        doc.text('CUSTOMER INFORMATION:', 14, yPos);
        yPos += lineHeight;
        
        doc.setFont('helvetica', 'normal');  
        doc.text('Customer Name: ' + orderData.customerName, 14, yPos);
        yPos += lineHeight;
        doc.text('Contact Number: ' + orderData.contact, 14, yPos);
        yPos += lineHeight;
        doc.text('Email Address: ' + orderData.email, 14, yPos);
        yPos += lineHeight;
        doc.text('Delivery Address: ' + orderData.address, 14, yPos);
        
        // Add footer
        const pageHeight = doc.internal.pageSize.height;
        doc.setFontSize(8);
        doc.setFont('helvetica', 'italic');
        doc.text('Thank you for your order!', 105, pageHeight - 20, null, null, 'center');
        doc.text('Page 1 of 1', 105, pageHeight - 10, null, null, 'center');
        
        // Save the PDF with a simple filename
        const fileName = 'Order_' + orderId + '.pdf';
        doc.save(fileName);
        
        console.log('PDF generated successfully for Order ID: ' + orderId);
        
    } catch (error) {
        console.error('Error generating PDF:', error);
        alert('Error generating PDF: ' + error.message);
    }
}
</script>

<style>
/* Additional styling for the print button */
.btn-primary:hover {
    background-color: #ff5252 !important;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    transition: all 0.3s ease;
}

.btn-primary {
    transition: all 0.3s ease;
}

.btn-primary i {
    margin-right: 8px;
}

/* Styling for individual PDF buttons */
.btn-pdf:hover {
    background-color: #218838 !important;
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    transition: all 0.3s ease;
}

.btn-pdf {
    transition: all 0.3s ease;
}

.btn-pdf i {
    margin-right: 4px;
}
</style>

<?php include('partials/footer.php'); ?>