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
    <title>Stall Book Section</title>
    <script src="https://cdn.tailwindcss.com"></script>
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

        #toast {
            z-index: 1000;
            transition: all 0.3s ease-in-out;
        }

        #toast.bg-green-100 {
            border-left: 4px solid #34D399;
        }

        #toast.bg-red-100 {
            border-left: 4px solid #F87171;
        }

        .translate-y-0 {
            transform: translateY(0);
        }

        .translate-y-full {
            transform: translateY(100%);
        }
    </style>
</head>

<body class="text-gray-800">
    <div class="container">
        <div class="card p-6">
            <h2 class="text-3xl font-bold text-[#ed1f24] mb-4">Book a Stall</h2>
            <form id="stallForm" action="process_stall_form.php" method="POST" class="space-y-4">
                <input type="hidden" name="formType" value="stallForm">
                <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
                
                <div>
                    <label class="block text-gray-700 font-semibold text-sm">Organization Name *</label>
                    <input type="text" required name="organizationName" class="form-input mt-1 block w-full" />
                    <span class="error-message">Organization name is required</span>
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold text-sm">Contact Person *</label>
                    <input type="text" required name="contactPerson" class="form-input mt-1 block w-full" />
                    <span class="error-message">Contact person name is required</span>
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold text-sm">Contact Number *</label>
                    <input type="tel" required pattern="[0-9]{10}" name="contactNumber" class="form-input mt-1 block w-full" />
                    <span class="error-message">Valid contact number required</span>
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold text-sm">Email Address *</label>
                    <input type="email" required name="email" class="form-input mt-1 block w-full" />
                    <span class="error-message">Valid email required</span>
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold text-sm">Business Type *</label>
                    <select required name="businessType" id="businessTypeSelect" class="form-input mt-1 block w-full">
                        <option value="">Select Business Type</option>
                        <option value="Food & Beverages">Food & Beverages</option>
                        <option value="Retail">Retail</option>
                        <option value="Arts & Crafts">Arts & Crafts</option>
                        <option value="Services">Services</option>
                        <option value="NGO/Non-Profit">NGO/Non-Profit</option>
                        <option value="Other">Other</option>
                    </select>
                    <span class="error-message">Business type is required</span>
                </div>

                <div id="otherBusinessTypeDiv" style="display: none;">
                    <label class="block text-gray-700 font-semibold text-sm">Specify Business Type *</label>
                    <input type="text" name="otherBusinessType" id="otherBusinessType" class="form-input mt-1 block w-full" />
                    <span class="error-message">Please specify your business type</span>
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold text-sm">Stall Size Preference *</label>
                    <select required name="stallSize" class="form-input mt-1 block w-full">
                        <option value="">Select Stall Size</option>
                        <option value="Small">Small</option>
                        <option value="Medium">Medium</option>
                        <option value="Large">Larges</option>
                    </select>
                    <span class="error-message">Stall size is required</span>
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold text-sm">What makes your stall unique? *</label>
                    <textarea required name="uniqueness" class="form-input mt-1 block w-full" rows="3" 
                        placeholder="Tell us what makes your stall unique..."></textarea>
                    <span class="error-message">Please tell us what makes your stall unique</span>
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold text-sm">Special Requirements</label>
                    <textarea name="specialRequirements" class="form-input mt-1 block w-full" rows="3" 
                        placeholder="Any special requirements for your stall..."></textarea>
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold text-sm">Address Details *</label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <input type="text" required name="houseNumber" placeholder="House/Building Number" 
                                class="form-input mt-1 block w-full" />
                            <span class="error-message">House/Building number is required</span>
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
                    <button type="submit" class="submit-btn w-full py-3 rounded-lg text-white text-lg font-semibold hover:shadow-xl">
                        Submit Stall Booking
                    </button>
                </div>

                <button type="button" class="submit-btn w-full py-3 rounded-lg text-white text-lg font-semibold hover:shadow-xl">
                    <a href="../join-us.html" class="menu-item rounded-xl p-4 cursor-pointer">
                        Back    
                    </a>
                </button>
            </form>
        </div>
    </div>

    <!-- Toast Container -->
    <div id="toast" class="fixed hidden right-4 bottom-4 px-6 py-4 rounded-lg shadow-lg transition-all duration-300 transform translate-y-full">
        <div class="flex items-center">
            <div id="toastMessage" class="text-gray-800"></div>
        </div>
    </div>

    <script>
        document.getElementById('businessTypeSelect').addEventListener('change', function() {
            const otherBusinessTypeDiv = document.getElementById('otherBusinessTypeDiv');
            const otherBusinessTypeInput = document.getElementById('otherBusinessType');
            
            if (this.value === 'Other') {
                otherBusinessTypeDiv.style.display = 'block';
                otherBusinessTypeInput.required = true;
            } else {
                otherBusinessTypeDiv.style.display = 'none';
                otherBusinessTypeInput.required = false;
                otherBusinessTypeInput.value = '';
            }
        });

        document.getElementById('stallForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const fields = this.querySelectorAll('input[required], select[required], textarea[required]');
            let valid = true;

            fields.forEach(field => {
                const errorMessage = field.nextElementSibling;
                if (!field.checkValidity()) {
                    errorMessage.classList.add('visible');
                    valid = false;
                } else {
                    errorMessage.classList.remove('visible');
                }
            });

            if (valid) {
                fetch('process_stall_form.php', {
                    method: 'POST',
                    body: new FormData(this)
                })
                .then(response => response.json())
                .then(data => {
                    const toast = document.getElementById('toast');
                    const toastMessage = document.getElementById('toastMessage');
                    
                    if (data.status === 'success') {
                        toast.classList.remove('bg-red-100');
                        toast.classList.add('bg-green-100');
                        this.reset();
                    } else {
                        toast.classList.remove('bg-green-100');
                        toast.classList.add('bg-red-100');
                    }
                    
                    toastMessage.textContent = data.message;
                    toast.classList.remove('hidden', 'translate-y-full');
                    toast.classList.add('translate-y-0');
                    
                    setTimeout(() => {
                        toast.classList.add('translate-y-full');
                        setTimeout(() => {
                            toast.classList.add('hidden');
                        }, 300);
                    }, 3000);
                })
                .catch(error => {
                    console.error('Error:', error);
                    const toast = document.getElementById('toast');
                    const toastMessage = document.getElementById('toastMessage');
                    
                    toast.classList.remove('bg-green-100');
                    toast.classList.add('bg-red-100');
                    toastMessage.textContent = 'An error occurred. Please try again.';
                    
                    toast.classList.remove('hidden', 'translate-y-full');
                    toast.classList.add('translate-y-0');
                    
                    setTimeout(() => {
                        toast.classList.add('translate-y-full');
                        setTimeout(() => {
                            toast.classList.add('hidden');
                        }, 300);
                    }, 3000);
                });
            }
        });
    </script>
</body>

</html>