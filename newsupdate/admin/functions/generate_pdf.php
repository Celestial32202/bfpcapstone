<?php
require_once('../plugin/TCPDF-main/tcpdf.php');
require_once('config.php'); // Include your database connection file
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
class CustomTCPDF extends TCPDF {
    // Page header
    public function Header() {
        // Path to your custom image
        $imageFile = '../img/magtanggo-logo.jpg';

        // Header HTML
        $header = '<table width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <td width="10%">
                    <img src="' . $imageFile . '" height="40" style="border: 1px solid black;"/>
                </td>
                <td width="70%" align="left" style="font-size: 12px;">
                    <b>Sangguniang Kabataan ng Baranggay Magtanggol</b><br>
                    <span>Registed Users</span><br>
                </td>
                <td width="20%"></td>
                
            </tr>
            <tr><td colspan="3" height="5"></td></tr>
        </table>
        <hr style="border: 1px solid black; ">';
        
        // Write the header
        $this->writeHTML($header, true, false, true, false, '');
    }
}
if (isset($_POST['user_ids']) && !empty($_POST['user_ids'])) {
    $userIds = $_POST['user_ids'];

    // Retrieve user details from the database
    $ids = implode(',', array_map('intval', $userIds));
    $sql = "SELECT * FROM users_creds WHERE id_counter IN ($ids)";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
        $emailAddresses = array_column($users, 'email');

        // Construct a comma-separated string of email addresses for use in the SQL query
        $emailList = "'" . implode("','", $emailAddresses) . "'";

        // Query to fetch additional rows based on the email addresses
        $sqlAdditional = "SELECT * FROM survey_information WHERE user_email IN ($emailList)";
        $resultAdditional = $conn->query($sqlAdditional);

        // Check if additional rows are fetched
        if ($resultAdditional->num_rows > 0) {
            // Fetch the additional rows
            $additionalRows = [];
            while ($row = $resultAdditional->fetch_assoc()) {
                $additionalRows[] = $row;
            }

            // Append additional rows to the $users array
            foreach ($users as &$user) {
                foreach ($additionalRows as $additionalRow) {
                    if ($user['email'] === $additionalRow['user_email']) {
                        $user = array_merge($user, $additionalRow);
                        break;
                    }
                }
            }
        }
        // Create new PDF document in landscape
        
        $pdf = new CustomTCPDF('L', PDF_UNIT,  array(216, 356), true, 'UTF-8', false);

        // Set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Sangguniang Kabataan ng Baranggay Magtanggol ');
        $pdf->SetTitle('User Details');
        $pdf->SetSubject('User Details');
        $pdf->SetKeywords('TCPDF, PDF, example, test, guide');

        // Set default header data
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

        // Set header and footer fonts
        $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // Set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // Set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // Set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // Set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // Add a page
        $pdf->AddPage();

        // Set font
        $pdf->SetFont('dejavusans', '', 10);

        // Content for the PDF
        $html = '<h1>User Details</h1><table border="1" cellspacing="2" cellpadding="3">';
        $html .= '<tr>
                    <th>First Name</th>
                    <th>Middle Name</th>
                    <th>Last Name</th>
                    <th>Birth Date</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th>Full Address</th>
                    <th>Civil Status</th>
                    <th>Age Group</th>
                    <th>Youth Class</th>
                    <th>Youth Class Needs</th>
                    <th>Work Status</th>
                    <th>Educational Background</th>
                    <th>Sk Voter?</th>
                    <th>Registered Voter?</th>
                </tr>';

        foreach ($users as $user) {
            $birthDate = new DateTime($user['birth_date']);
            $formattedBirthDate = $birthDate->format('F d Y');
            $html .= '<tr>';
            $html .= '<td>' . $user['first_name'] . '</td>';
            $html .= '<td>' . $user['middle_name'] . '</td>';
            $html .= '<td>' . $user['last_name'] . '</td>';
            $html .= '<td>' . $formattedBirthDate . '</td>';
            $html .= '<td>' . $user['email'] . '</td>';
            $html .= '<td>' . $user['phone_number'] . '</td>';
            $html .= '<td>' . (isset($user['full_address']) ? $user['full_address'] : 'not filled') . '</td>';
            $html .= '<td>' . (isset($user['civil_status']) ? $user['civil_status'] : 'not filled') . '</td>';
            $html .= '<td>' . (isset($user['age_group']) ? $user['age_group'] : 'not filled') . '</td>';
            $html .= '<td>' . (isset($user['youth_class']) ? $user['youth_class'] : 'not filled') . '</td>';
            $html .= '<td>' . (isset($user['youth_class_needs']) ? $user['youth_class_needs'] : 'not filled') . '</td>';
            $html .= '<td>' . (isset($user['work_status']) ? $user['work_status'] : 'not filled') . '</td>';
            $html .= '<td>' . (isset($user['educ_background']) ? $user['educ_background'] : 'not filled') . '</td>';
            $html .= '<td>' . (isset($user['sk_voter']) ? $user['sk_voter'] : 'not filled') . '</td>';
            $html .= '<td>' . (isset($user['voted']) ? $user['voted'] : 'not filled') . '</td>';

            $html .= '</tr>';
        }

        $html .= '</table>';

        // Print text using writeHTMLCell()
        $pdf->writeHTML($html, true, false, true, false, '');

        // Close and output PDF document
        $pdf->Output('user_details.pdf', 'I');
    } else {
        echo 'No users found.';
    }
} else {
    echo 'No user selected.';
}
?>
