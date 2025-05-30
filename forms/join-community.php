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
    <title>Join Our Team</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <link rel="icon" type="image/png" href="../assets/logo.png">

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

        .membership-card {
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
        }

        .membership-card img {
            width: 80px;
            margin: 0 auto 1rem;
        }

        .membership-card h3 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .membership-card p {
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
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
            <h2 class="text-3xl font-bold text-[#ed1f24] mb-4">Join Our Team</h2>
            <form id="joinForm" action="process_join_form.php" method="POST" class="space-y-4">
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
                    <label class="block text-gray-700 font-semibold text-sm">Blood Group *</label>
                    <select required name="bloodGroup" class="form-input mt-1 block w-full">
                        <option value="">Select Blood Group</option>
                        <option value="A+">A+</option>
                        <option value="A-">A-</option>
                        <option value="B+">B+</option>
                        <option value="B-">B-</option>
                        <option value="AB+">AB+</option>
                        <option value="AB-">AB-</option>
                        <option value="O+">O+</option>
                        <option value="O-">O-</option>
                    </select>
                    <span class="error-message">Blood group is required</span>
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold text-sm">Profession *</label>
                    <input type="text" required name="profession" class="form-input mt-1 block w-full" />
                    <span class="error-message">Profession is required</span>
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
                    <label class="block text-gray-700 font-semibold text-sm">Field of Interest *</label>
                    <select required name="interest" id="interestSelect" class="form-input mt-1 block w-full">
                        <option value="">Select an option</option>
                        <option value="content">Content Writing</option>
                        <option value="design">Designing</option>
                        <option value="dance">Dance</option>
                        <option value="art">Art</option>
                        <option value="others">Others</option>
                    </select>
                    <span class="error-message">Please select a field</span>
                </div>
                <div id="otherInterestDiv" style="display: none;">
                    <label class="block text-gray-700 font-semibold text-sm">Specify Your Interest *</label>
                    <input type="text" name="otherInterest" id="otherInterest" class="form-input mt-1 block w-full" />
                    <span class="error-message">Please specify your interest</span>
                </div>
                <div>
                    <button type="submit" class="submit-btn w-full py-3 rounded-lg text-white text-lg font-semibold hover:shadow-xl">Submit</button>
                </div>

                <button type="button" class="submit-btn w-full py-3 rounded-lg text-white text-lg font-semibold hover:shadow-xl">
                    <a href="../join-us.html" class="menu-item rounded-xl p-4 cursor-pointer">
                        Back    
                    </a>
                </button>
            </form>
        </div>

        <div class="membership-card" id="membershipCard">
            <img src="../assets/logo.png" alt="Trust Logo">
            <h3>Gurjar Art and Cultural Trust</h3>
            <p id="cardMemberId"></p>
            <p id="cardName"></p>
            <p id="cardContact"></p>
            <p id="cardEmail"></p>
            <p id="cardBloodGroup"></p>
            <p id="cardDate"></p>
            <a href="#" id="downloadBtn" class="download-btn">Download Card</a>
        </div>
    </div>

    <div id="formError" class="hidden text-red-500 text-center mt-4"></div>

    <div id="toast" class="fixed bottom-5 right-5 px-6 py-3 rounded-lg shadow-lg transform transition-all duration-500 opacity-0 translate-y-full">
        <span id="toastMessage"></span>
    </div>

    <script>
        document.getElementById('joinForm').addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(this);
            
            fetch('process_join_form.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update membership card
                    document.getElementById('cardName').textContent = `Name: ${data.data.name}`;
                    document.getElementById('cardContact').textContent = `Contact: ${data.data.contact}`;
                    document.getElementById('cardEmail').textContent = `Email: ${data.data.email}`;
                    document.getElementById('cardBloodGroup').textContent = `Blood Group: ${data.data.bloodGroup}`;
                    document.getElementById('cardDate').textContent = `Date Joined: ${data.data.dateJoined}`;
                    document.getElementById('cardMemberId').textContent = `Member ID: ${data.data.memberId}`;
                    
                    // Show membership card and scroll to it
                    const card = document.getElementById('membershipCard');
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

        document.getElementById('interestSelect').addEventListener('change', function () {
            const otherInterestDiv = document.getElementById('otherInterestDiv');
            const otherInterestInput = document.getElementById('otherInterest');

            if (this.value === 'others') {
                otherInterestDiv.style.display = 'block';
                otherInterestInput.required = true;
            } else {
                otherInterestDiv.style.display = 'none';
                otherInterestInput.required = false;
                otherInterestInput.value = ''; // Clear the input when hidden
            }
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

        // PDF generation for membership card
        document.getElementById('downloadBtn').addEventListener('click', function(e) {
            e.preventDefault();
            
            const { jsPDF } = window.jspdf;
            
            // Create a new PDF document
            const doc = new jsPDF({
                orientation: 'portrait',
                unit: 'mm',
                format: 'a6'
            });
            
            // Capture the membership card as an image
            html2canvas(document.getElementById('membershipCard')).then(canvas => {
                const imgData = canvas.toDataURL('image/png');
                
                // Add the image to the PDF
                doc.addImage(imgData, 'PNG', 10, 10, 90, 120);
                
                // Save the PDF
                doc.save('GACT_Membership_Card.pdf');
            });
        });
    </script>
</body>

</html>