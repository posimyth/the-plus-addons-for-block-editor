document.addEventListener("DOMContentLoaded", function () {
    const formBlocks = document.querySelectorAll(".tp-form-block");
    formBlocks.forEach((formBlock) => {
        let formDataAttributes = {};
        let isSubmitting = false;

        const formId = formBlock.getAttribute("data-block-id");
        if (formId) {
            const validateEmail = (emailInput, errorShow) => {
                if (
                    !emailInput ||
                    !emailInput.hasAttribute("data-required") ||
                    emailInput.getAttribute("data-required") === "false"
                ) {
                    return true;
                }
                const emailValue = emailInput.value.trim();
                const customErrorMessage =
                    errorShow.getAttribute("data-error-message");
                if (!emailValue) {
                    errorShow.textContent =
                        customErrorMessage || "This field is required.";
                    return false;
                }

                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(emailValue)) {
                    errorShow.textContent =
                        customErrorMessage ||
                        "Please enter a valid email address.";
                    return false;
                }

                errorShow.textContent = "";
                return true;
            };

            const validateMessage = (messageTextarea, errorShow) => {
                if (
                    !messageTextarea ||
                    !messageTextarea.hasAttribute("data-required") ||
                    messageTextarea.getAttribute("data-required") === "false"
                ) {
                    return true;
                }
                const messageValue = messageTextarea.value.trim();
                const customErrorMessage =
                    errorShow.getAttribute("data-error-message");
                if (!messageValue) {
                    errorShow.textContent =
                        customErrorMessage || "This field is required.";
                    return false;
                }
                errorShow.textContent = "";
                return true;
            };

            const validateCheckbox = (input, errorShow) => {
                if (
                    !input ||
                    !input.hasAttribute("data-required") ||
                    input.getAttribute("data-required") === "false"
                ) {
                    if (errorShow) {
                        errorShow.textContent = "";
                    }
                    return true;
                }

                const container = input.closest(
                    ".tp-form-checkbox-button, .tp-form-radio-button"
                );
                if (!container) return true;

                let isChecked = false;

                if (input.type === "radio") {
                    // For radio buttons
                    const radios = container.querySelectorAll(
                        `input[type="radio"][name="${input.name}"]`
                    );
                    isChecked = Array.from(radios).some(
                        (radio) => radio.checked
                    );
                } else {
                    // For checkboxes
                    const checkboxes = container.querySelectorAll(
                        `input[name="${input.name}"]`
                    );
                    isChecked = Array.from(checkboxes).some((cb) => cb.checked);
                }

                let customErrorMessage = "This field is required.";
                if (errorShow && typeof errorShow.getAttribute === "function") {
                    const msg = errorShow.getAttribute("data-error-message");
                    if (msg) {
                        customErrorMessage = msg;
                    }
                }

                if (!isChecked) {
                    if (errorShow) {
                        errorShow.textContent = customErrorMessage;
                    }
                    return false;
                } else {
                    if (errorShow) {
                        errorShow.textContent = "";
                    }
                    return true;
                }
            };

            const nameInputs = formBlock.querySelectorAll(".nxt-name-richtext");
            const emailInputs = formBlock.querySelectorAll(
                ".nxt-email-richtext"
            );
            const messageTextareas = formBlock.querySelectorAll(
                ".nxt-message-richtext"
            );
            const checkboxGroups = formBlock.querySelectorAll(
                ".tp-form-checkbox-button"
            );

            const radioGroups = formBlock.querySelectorAll(
                ".tp-form-radio-button "
            );
            const selectFields =
                formBlock.querySelectorAll(".nxt-option-field");
            const numberInputs = formBlock.querySelectorAll(
                ".nxt-number-richtext"
            );

            // Select corresponding error spans
            const errorShows = formBlock.querySelectorAll(".nxt-error-show");
            const errorShowsEmail =
                formBlock.querySelectorAll(".nxt-error-email");
            const errorShowsMessage =
                formBlock.querySelectorAll(".nxt-message-error");
            const errorShowsCheckbox = formBlock.querySelectorAll(
                ".nxt-error-checkbox"
            );
            const errorShowsRadio = formBlock.querySelectorAll(
                ".nxt-error-radiobox"
            );
            const errorShowsSelect =
                formBlock.querySelectorAll(".nxt-error-select");
            const errorShowsNumber =
                formBlock.querySelectorAll(".nxt-error-number");

            // Attach event listeners for real-time validation
            nameInputs.forEach((input, index) => {
                input.addEventListener("input", () =>
                    validateMessage(input, errorShows[index])
                );
            });

            emailInputs.forEach((input, index) => {
                input.addEventListener("input", () =>
                    validateEmail(input, errorShowsEmail[index])
                );
            });

            messageTextareas.forEach((textarea, index) => {
                textarea.addEventListener("input", () =>
                    validateMessage(textarea, errorShowsMessage[index])
                );
            });

            checkboxGroups.forEach((group) => {
                const checkboxes = group.querySelectorAll(
                    'input[type="checkbox"]'
                );
                const errorElement = group.querySelector(".nxt-error-checkbox");
                checkboxes.forEach((checkbox) => {
                    checkbox.addEventListener("change", () =>
                        validateCheckbox(checkbox, errorElement)
                    );
                });
            });

            radioGroups.forEach((group) => {
                const radio = group.querySelectorAll('input[type="radio"]');
                const errorElement = group.querySelector(".nxt-error-radiobox");
                radio.forEach((radio) => {
                    radio.addEventListener("change", () =>
                        validateCheckbox(radio, errorElement)
                    );
                });
            });

            selectFields.forEach((input, index) => {
                input.addEventListener("change", () =>
                    validateMessage(input, errorShowsSelect[index])
                );
            });

            numberInputs.forEach((input, index) => {
                input.addEventListener("input", () =>
                    validateMessage(input, errorShowsNumber[index])
                );
            });

            // Form Validation Function
            const isFormValid = () => {
                let isValid = true;

                nameInputs.forEach((nameInput, index) => {
                    if (nameInput.hasAttribute("data-required")) {
                        const isNameValid = validateMessage(
                            nameInput,
                            errorShows[index]
                        );
                        isValid = isValid && isNameValid;
                    }
                });

                emailInputs.forEach((emailInput, index) => {
                    if (emailInput.hasAttribute("data-required")) {
                        const isEmailValid = validateEmail(
                            emailInput,
                            errorShowsEmail[index]
                        );
                        isValid = isValid && isEmailValid;
                    }
                });

                messageTextareas.forEach((messageTextarea, index) => {
                    if (messageTextarea.hasAttribute("data-required")) {
                        const isMessageValid = validateMessage(
                            messageTextarea,
                            errorShowsMessage[index]
                        );
                        isValid = isValid && isMessageValid;
                    }
                });

                checkboxGroups.forEach((group) => {
                    const firstCheckbox = group.querySelector(
                        'input[type="checkbox"]'
                    );
                    const errorElement = group.querySelector(
                        ".nxt-error-checkbox"
                    );

                    if (
                        firstCheckbox &&
                        firstCheckbox.hasAttribute("data-required")
                    ) {
                        const isCheckboxValid = validateCheckbox(
                            firstCheckbox,
                            errorElement
                        );
                        isValid = isValid && isCheckboxValid;
                    }
                });

                radioGroups.forEach((group) => {
                    const firstRadio = group.querySelector(
                        'input[type="radio"]'
                    );
                    const errorElement = group.querySelector(
                        ".nxt-error-radiobox"
                    );

                    if (
                        firstRadio &&
                        firstRadio.hasAttribute("data-required")
                    ) {
                        const isRadioValid = validateCheckbox(
                            firstRadio,
                            errorElement
                        );
                        isValid = isValid && isRadioValid;
                    }
                });

                selectFields.forEach((selectField, index) => {
                    if (selectField.hasAttribute("data-required")) {
                        const isSelectValid = validateMessage(
                            selectField,
                            errorShowsSelect[index]
                        );
                        isValid = isValid && isSelectValid;
                    }
                });

                numberInputs.forEach((numberInput, index) => {
                    if (numberInput.hasAttribute("data-required")) {
                        const isNumberValid = validateMessage(
                            numberInput,
                            errorShowsNumber[index]
                        );
                        isValid = isValid && isNumberValid;
                    }
                });

                return isValid;
            };

            const formElement = formBlock.querySelector(".nxt-form");
            if (formElement) {
                formDataAttributes =
                    formElement.getAttribute("data-formdata") || "";
                formElement.addEventListener("submit", handleSubmit);
            }
            function handleSubmit(event) {
                event.preventDefault();
                if (!isFormValid()) {
                    return;
                }
                if (isSubmitting) {
                    return;
                }

                isSubmitting = true;
                submitForm(formDataAttributes);
                function submitForm(formDataAttributes) {
                    const formData = new FormData();

                    const getFieldValue = (name) => {
                        if (name === "checkbox") {
                            const checkboxContainer = formBlock.querySelector(
                                ".tp-form-checkbox-button"
                            );
                            if (checkboxContainer) {
                                const titleLabel =
                                    checkboxContainer.querySelector(
                                        ".nxt-check-title-label"
                                    );
                                const label = titleLabel
                                    ? titleLabel.innerText.trim()
                                    : name;

                                const checkedBoxes = Array.from(
                                    checkboxContainer.querySelectorAll(
                                        'input[type="checkbox"]:checked'
                                    )
                                ).map((checkbox) => checkbox.value);

                                if (checkedBoxes.length > 0) {
                                    return [
                                        {
                                            label,
                                            value: checkedBoxes.join(", "),
                                        },
                                    ];
                                }
                            }
                            return [];
                        }

                        return Array.from(
                            formBlock.querySelectorAll(
                                `input[name='${name}'], textarea[name='${name}'], select[name='${name}']`
                            )
                        )
                            .filter((field) => {
                                if (field.type === "radio") {
                                    return field.checked;
                                }
                                return field.value;
                            })
                            .map((field) => {
                                const parentContainer =
                                    field.type === "email"
                                        ? field.closest(".nxt-email-input")
                                        : field.classList.contains(
                                              "nxt-name-richtext"
                                          )
                                        ? field.closest(".nxt-name-input")
                                        : field.type === "number"
                                        ? field.closest(".nxt-number-input")
                                        : field.parentElement;
                                const labelEl = parentContainer
                                    ? parentContainer.querySelector("label")
                                    : null;
                                return {
                                    value: field.value,
                                    label: labelEl
                                        ? labelEl.innerText.trim()
                                        : name,
                                };
                            });
                    };

                    [
                        "text-field",
                        "email",
                        "message",
                        "number",
                        "select",
                        "checkbox",
                    ].forEach((fieldName) =>
                        getFieldValue(fieldName).forEach((field) => {
                            formData.append(field.label, field.value);
                        })
                    );

                    // Handle radio groups separately
                    Array.from(
                        formBlock.querySelectorAll('input[type="radio"]')
                    ).forEach((radio) => {
                        if (
                            radio.checked &&
                            radio.name.startsWith("radio-group")
                        ) {
                            const radioContainer = radio.closest(
                                ".tp-form-radio-button"
                            );
                            const titleLabel = radioContainer
                                ? radioContainer.querySelector(
                                      ".nxt-radio-title-label"
                                  )
                                : null;
                            const label = titleLabel
                                ? titleLabel.innerText.trim()
                                : radio.name;
                            formData.append(label, radio.value);
                        }
                    });

                    let cap = formBlock.querySelector(
                        '[name="cf-turnstile-response"],[name="g-recaptcha-response"] '
                    );

                    formData.append("actionOption", formDataAttributes || []);
                    formData.append("action", "nxt_form_action");
                    formData.append("nonce", tpgb_config.tpgb_nonce);
                    if (cap) {
                        formData.append("cf-turnstile-response", cap.value);
                    }
                    const actionOption =
                        formElement.hasAttribute("data-actionoption") &&
                        formElement.getAttribute("data-actionoption").trim() !==
                            ""
                            ? JSON.parse(
                                  formElement.getAttribute("data-actionoption")
                              ).map((item) => item.value)
                            : "";
                    let redirect = "";
                    let hasEmail = "";
                    let linkBlnk;
                    let lnkNoFlw;
                    const showMessage = (message, isError) => {
                        const messageElement = formBlock.querySelector(
                            ".nxt-success-message"
                        );
                        if (messageElement) {
                            messageElement.textContent = message;
                            messageElement.classList.remove(
                                "nxt-success-message",
                                "error-message"
                            );
                            messageElement.classList.add(
                                isError
                                    ? "error-message"
                                    : "nxt-success-message"
                            );
                            messageElement.style.display = "block";
                        }
                    };

                    if (actionOption && Array.isArray(actionOption)) {
                        const hasRedirectOption = actionOption.some(
                            (option) => option.toLowerCase() === "redirect"
                        );

                        if (
                            hasRedirectOption &&
                            formElement.hasAttribute("data-redirect")
                        ) {
                            redirect =
                                formElement.getAttribute("data-redirect");
                        }
                        if (
                            hasRedirectOption &&
                            formElement.hasAttribute("data-link-blank")
                        ) {
                            linkBlnk =
                                formElement.getAttribute("data-link-blank");
                        }
                        if (
                            hasRedirectOption &&
                            formElement.hasAttribute("data-link-nofollow")
                        ) {
                            lnkNoFlw =
                                formElement.getAttribute("data-link-nofollow");
                        }
                        const isEmail = actionOption.some(
                            (option) => option.toLowerCase() === "email"
                        );
                        if (isEmail) {
                            hasEmail = true;
                        }
                    }
                    const isValidURL = (string) => {
                        try {
                            new URL(string);
                            return true;
                        } catch (_) {
                            return false;
                        }
                    };

                    if (!actionOption || actionOption.length === 0) {
                        showMessage("No form action is selected.", true);
                        isSubmitting = false;
                        return;
                    }

                    if (isFormValid()) {
                        if (hasEmail) {
                            const submitButton =
                                formBlock.querySelector(".nxt-submit");
                            const loaderSpan =
                                submitButton.querySelector(".nxt-loader");
                            const buttonSvg =
                                submitButton.querySelector(".nxt-button-svg");
                            let buttonText =
                                submitButton.querySelector(".nxt-btn-text");
                            buttonText ? (buttonText.style.opacity = "0") : "";
                            buttonSvg ? (buttonSvg.style.opacity = "0") : "";
                            loaderSpan.style.opacity = "1";

                            fetch(tpgb_config.ajax_url, {
                                method: "POST",
                                body: formData,
                                credentials: "same-origin",
                                headers: {
                                    "X-WP-Nonce": tpgb_config.tpgb_nonce,
                                },
                            })
                                .then((response) => response.json())
                                .then((responseData) => {
                                    isSubmitting = false;
                                    loaderSpan.style.opacity = "0";
                                    buttonSvg
                                        ? (buttonSvg.style.opacity = "1")
                                        : "";
                                    buttonText
                                        ? (buttonText.style.opacity = "1")
                                        : "";

                                    if (responseData.success) {
                                        const successMessage =
                                            formBlock
                                                .querySelector(
                                                    ".nxt-success-message"
                                                )
                                                .getAttribute(
                                                    "data-success-message"
                                                ) ||
                                            "Your submission was successful.";
                                        showMessage(successMessage, false);
                                        // Reset all form fields
                                        const formFields =
                                            formBlock.querySelectorAll(
                                                "input, textarea, select"
                                            );
                                        formFields.forEach((field) => {
                                            if (
                                                field.type === "checkbox" ||
                                                field.type === "radio"
                                            ) {
                                                field.checked = false;
                                            } else {
                                                field.value = "";
                                            }
                                        });
                                    } else {
                                        const errorMessage =
                                            responseData.data ||
                                            "Unknown error occurred.";
                                        showMessage(errorMessage, true);
                                        loaderSpan.style.opacity = "0";
                                        buttonSvg
                                            ? (buttonSvg.style.opacity = "1")
                                            : "";
                                        buttonText
                                            ? (buttonTextstyle.opacity = "1")
                                            : "";
                                    }
                                })
                                .catch((error) => {
                                    loaderSpan.style.opacity = "0";
                                    buttonSvg
                                        ? (buttonSvgstyle.opacity = "1")
                                        : "";
                                    buttonText
                                        ? (buttonTextstyle.opacity = "1")
                                        : "";
                                    showMessage(
                                        "An error occurred while sending the form data.",
                                        true
                                    );
                                    isSubmitting = false;
                                });
                        }
                        setTimeout(() => {
                            if (redirect && isValidURL(redirect)) {
                                if (linkBlnk === "1") {
                                    let newWindow = window.open("", "_blank");

                                    if (lnkNoFlw === "1") {
                                        let linkElement =
                                            newWindow.document.createElement(
                                                "a"
                                            );
                                        linkElement.setAttribute(
                                            "href",
                                            redirect
                                        );
                                        linkElement.setAttribute(
                                            "rel",
                                            "nofollow"
                                        );
                                        newWindow.document.body.appendChild(
                                            linkElement
                                        );

                                        linkElement.click();
                                    } else {
                                        newWindow.location.href = redirect;
                                    }
                                } else {
                                    window.location.href = redirect;
                                }
                            }
                        }, 2000);
                    }
                }
            }
        }
    });
});
