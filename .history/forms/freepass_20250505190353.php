<?php
// Include security utilities
require_once '../includes/security.php';

// Set security headers
setSecurityHeaders();

// Generate CSRF token
$csrfToken = generateCsrfToken();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Get Free Pass</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <link rel="icon" type="image/png" href="../assets/logo.png">
    <script src="../js/security.js"></script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #FF6B6B, #FFE66D);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 0;
            flex-direction: column;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
            width: 100%;
        }

        .card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(31, 38, 135, 0.37);
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.18);
            transition: all 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
            padding: 2rem;
            position: relative;
            overflow: hidden;
            margin-bottom: 2rem;
        }

        .form-input {
            transition: all 0.3s ease;
            border: 1px solid #e2e8f0;
            padding: 0.5rem 1rem;
        }

        .form-input:focus {
            transform: scale(1.01);
            box-shadow: 0 0 0 3px rgba(255, 107, 107, 0.3);
            outline: none;
        }

        .submit-btn {
            background-color: #ed1f24;
            transition: all 0.3s ease;
            margin-top: 1rem;
            cursor: pointer;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 107, 107, 0.4);
        }

        .error-message {
            color: #ed1f24;
            font-size: 0.75rem;
            margin-top: 0.25rem;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .error-message.visible {
            opacity: 1;
        }

        .pass-card {
            background: linear-gradient(135deg, #ed1f24, #ff6b6b);
            color: white;
            border-radius: 15px;
            padding: 2rem;
            width: 100%;
            max-width: 400px;
            margin: 0 auto;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            display: none;
            text-align: center;
            position: relative;
        }

        .pass-card img.logo {
            width: 80px;
            margin: 0 auto 1rem;
        }

        .pass-card h3 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .pass-card p {
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }

        .pass-card .qr-code {
            margin: 1rem auto;
            background: white;
            padding: 1rem;
            border-radius: 10px;
            width: 150px;
            height: 150px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .download-btn {
            display: inline-block;
            background: white;
            color: #ed1f24;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            margin-top: 1rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .download-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        #toast {
            background-color: white;
            color: #4a5568;
            border-radius: 0.5rem;
            position: fixed;
            bottom: 1rem;
            right: 1rem;
            padding: 1rem 2rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transform: translateY(100%);
            opacity: 0;
            transition: all 0.5s ease;
        }

        #toast.success {
            border-left: 4px solid #48bb78;
        }

        #toast.error {
            border-left: 4px solid #f56565;
        }

        #toast.visible {
            transform: translateY(0);
            opacity: 1;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="card">
            <h2 class="text-3xl font-bold text-[#ed1f24] mb-4">Get Your Free Pass</h2>
            <form id="passForm" action="process_freepass_form.php" method="POST" class="space-y-4">
                <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
                
                <div>
                    <label class="block text-gray-700 font-semibold text-sm">Full Name *</label>
                    <input type="text" required name="name" class="form-input mt-1 block w-full" />
                    <span class="error-message">Name is required</span>
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold text-sm">Contact Number *</label>
                    <input type="tel" required pattern="[0-9]{10}" name="contact" class="form-input mt-1 block w-full" />
                    <span class="error-message">Valid contact number required</span>
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold text-sm">Email Address *</label>
                    <input type="email" required name="email" class="form-input mt-1 block w-full" />
                    <span class="error-message">Valid email required</span>
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold text-sm">Age Group *</label>
                    <select required name="ageGroup" class="form-input mt-1 block w-full">
                        <option value="">Select Age Group</option>
                        <option value="under18">Under 18</option>
                        <option value="18-25">18-25</option>
                        <option value="26-35">26-35</option>
                        <option value="36-50">36-50</option>
                        <option value="above50">Above 50</option>
                    </select>
                    <span class="error-message">Age group is required</span>
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold text-sm">Address Details *</label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <input type="text" required name="houseNumber" placeholder="House/Flat Number" 
                                class="form-input mt-1 block w-full" />
                            <span class="error-message">House/Flat number is required</span>
                        </div>
                        <div>
                            <input type="text" required name="lane" placeholder="Street/Lane" 
                                class="form-input mt-1 block w-full" />
                            <span class="error-message">Street/Lane is required</span>
                        </div>
                        <div>
                            <input type="text" required name="city" placeholder="City" 
                                class="form-input mt-1 block w-full" />
                            <span class="error-message">City is required</span>
                        </div>
                        <div>
                            <select required name="state" class="form-input mt-1 block w-full">
                                <option value="">Select State</option>
                                <option value="Andaman and Nicobar Islands">Andaman and Nicobar Islands</option>
                                <option value="Andhra Pradesh">Andhra Pradesh</option>
                                <option value="Arunachal Pradesh">Arunachal Pradesh</option>
                                <option value="Assam">Assam</option>
                                <option value="Bihar">Bihar</option>
                                <option value="Chandigarh">Chandigarh</option>
                                <option value="Chhattisgarh">Chhattisgarh</option>
                                <option value="Dadra and Nagar Haveli and Daman and Diu">Dadra and Nagar Haveli and Daman and Diu</option>
                                <option value="Delhi">Delhi</option>
                                <option value="Goa">Goa</option>
                                <option value="Gujarat">Gujarat</option>
                                <option value="Haryana">Haryana</option>
                                <option value="Himachal Pradesh">Himachal Pradesh</option>
                                <option value="Jammu and Kashmir">Jammu and Kashmir</option>
                                <option value="Jharkhand">Jharkhand</option>
                                <option value="Karnataka">Karnataka</option>
                                <option value="Kerala">Kerala</option>
                                <option value="Ladakh">Ladakh</option>
                                <option value="Lakshadweep">Lakshadweep</option>
                                <option value="Madhya Pradesh">Madhya Pradesh</option>
                                <option value="Maharashtra">Maharashtra</option>
                                <option value="Manipur">Manipur</option>
                                <option value="Meghalaya">Meghalaya</option>
                                <option value="Mizoram">Mizoram</option>
                                <option value="Nagaland">Nagaland</option>
                                <option value="Odisha">Odisha</option>
                                <option value="Puducherry">Puducherry</option>
                                <option value="Punjab">Punjab</option>
                                <option value="Rajasthan">Rajasthan</option>
                                <option value="Sikkim">Sikkim</option>
                                <option value="Tamil Nadu">Tamil Nadu</option>
                                <option value="Telangana">Telangana</option>
                                <option value="Tripura">Tripura</option>
                                <option value="Uttar Pradesh">Uttar Pradesh</option>
                                <option value="Uttarakhand">Uttarakhand</option>
                                <option value="West Bengal">West Bengal</option>
                            </select>
                            <span class="error-message">State is required</span>
                        </div>
                        <div>
                            <input type="text" required name="pincode" placeholder="PIN Code" 
                                pattern="[0-9]{6}" class="form-input mt-1 block w-full" />
                            <span class="error-message">Valid 6-digit PIN code required</span>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold text-sm">How did you hear about us? *</label>
                    <select required name="source" class="form-input mt-1 block w-full">
                        <option value="">Select an option</option>
                        <option value="social">Social Media</option>
                        <option value="friend">Friend/Family</option>
                        <option value="newspaper">Newspaper</option>
                        <option value="radio">Radio</option>
                        <option value="other">Other</option>
                    </select>
                    <span class="error-message">Please select an option</span>
                </div>

                <div>
                    <button type="submit" class="submit-btn w-full py-3 rounded-lg text-white text-lg font-semibold hover:shadow-xl">Get Free Pass</button>
                </div>

                <button type="button" class="submit-btn w-full py-3 rounded-lg text-white text-lg font-semibold hover:shadow-xl">
                    <a href="../join-us.html" class="menu-item rounded-xl p-4 cursor-pointer">
                        Back    
                    </a>
                </button>
            </form>
        </div>

        <div class="pass-card" id="passCard">
            <img src="../assets/logo.png" alt="Trust Logo" class="logo">
            <h3>Gurjar Art and Cultural Trust</h3>
            <h4 class="text-xl font-bold mb-2">FREE ENTRY PASS</h4>
            <p id="cardName"></p>
            <p id="cardContact"></p>
            <p id="cardEmail"></p>
            <p id="cardAgeGroup"></p>
            <div class="qr-code" id="qrCode"></div>
            <p id="cardPassId"></p>
            <p id="cardDate"></p>
            <a href="#" id="downloadBtn" class="download-btn">Download Pass</a>
        </div>
    </div>

    <div id="formError" class="hidden text-red-500 text-center mt-4"></div>

    <div id="toast" class="fixed bottom-5 right-5 px-6 py-3 rounded-lg shadow-lg transform transition-all duration-500 opacity-0 translate-y-full">
        <span id="toastMessage"></span>
    </div>

    <script>
        document.getElementById('passForm').addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(this);
            
            fetch('process_freepass_form.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update pass card
                    document.getElementById('cardName').textContent = `Name: ${data.data.name}`;
                    document.getElementById('cardContact').textContent = `Contact: ${data.data.contact}`;
                    document.getElementById('cardEmail').textContent = `Email: ${data.data.email}`;
                    document.getElementById('cardAgeGroup').textContent = `Age Group: ${data.data.ageGroup}`;
                    document.getElementById('cardPassId').textContent = `Pass ID: ${data.data.passId}`;
                    document.getElementById('cardDate').textContent = `Date: ${data.data.date}`;
                    
                    // Generate QR code
                    const qrCodeDiv = document.getElementById('qrCode');
                    qrCodeDiv.innerHTML = '';
                    new QRCode(qrCodeDiv, {
                        text: `GACT-PASS:${data.data.passId}`,
                        width: 128,
                        height: 128
                    });
                    
                    // Show pass card and scroll to it
                    const card = document.getElementById('passCard');
                    card.style.display = 'block';
                    card.scrollIntoView({ behavior: 'smooth' });
                    
                    // Show success toast
                    showToast(data.message, 'success');
                    
                    // Reset form
                    this.reset();
                    
                } else {
                    showToast(data.message, 'error');
                }
            })
            .catch(error => {
                showToast('An error occurred. Please try again.', 'error');
                console.error('Error:', error);
            });
        });

        function showToast(message, type) {
            const toast = document.getElementById('toast');
            const toastMessage = document.getElementById('toastMessage');
            
            toast.className = 'fixed bottom-5 right-5 px-6 py-3 rounded-lg shadow-lg';
            toast.classList.add(type);
            toastMessage.textContent = message;
            
            // Show toast
            toast.classList.add('visible');
            
            // Hide toast after 3 seconds
            setTimeout(() => {
                toast.classList.remove('visible');
            }, 3000);
        }

        // PDF generation for pass card
        document.getElementById('downloadBtn').addEventListener('click', function(e) {
            e.preventDefault();
            
            const { jsPDF } = window.jspdf;
            
            // Create a new PDF document
            const doc = new jsPDF({
                orientation: 'portrait',
                unit: 'mm',
                format: 'a6'
            });
            
            // Capture the pass card as an image
            html2canvas(document.getElementById('passCard')).then(canvas => {
                const imgData = canvas.toDataURL('image/png');
                
                // Add the image to the PDF
                doc.addImage(imgData, 'PNG', 10, 10, 90, 120);
                
                // Save the PDF
                doc.save('GACT_Free_Pass.pdf');
            });
        });
    </script>
</body>

</html>