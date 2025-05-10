export function validatePrice(input, minInput, maxInput, e) {
  let maxValue = parseInt(maxInput.value) || 0;
  let start = input.selectionStart;
  let end = input.selectionEnd;
  let nextValue;
  let val = input.value;

  if (e.inputType === "deleteContentBackward") {
    nextValue = val.slice(0, start - 1) + val.slice(end);
  } else if (e.inputType === "deleteContentForward") {
    nextValue = val.slice(0, start) + val.slice(end + 1);
  } else {
    nextValue = val.slice(0, start) + (e.data ?? "") + val.slice(end);

    // Ensure only digits
    if (!/^\d*$/.test(nextValue) || nextValue.length > 7) {
      e.preventDefault();
      return;
    }

    // Remove leading zeroes
    if (val && val.charAt(0) === "0") {
      val = val.slice(1);
    }
  }

  if (input === minInput) {
    maxInput.value = Math.max(maxValue, nextValue);
  }
  resetBorder(maxInput);
}

// Validate price range 'maximum' input
export function resetBorder(maxInput) {
  maxInput.style.border = "";
  maxInput.removeEventListener("input", resetBorder);
}

export function highlightBorderError(maxInput){
    maxInput.style.border = "2px solid var(--error-colour)";
    maxInput.addEventListener("input", () => resetBorder(maxInput));
}