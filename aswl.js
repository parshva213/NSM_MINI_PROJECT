function showSuccessPopup(message, duration = 10000) {
    const popup = document.createElement('div');
    popup.id = 'successPopup';
    popup.style.position = 'fixed';
    popup.style.top = '50%';
    popup.style.left = '50%';
    popup.style.transform = 'translate(-50%, -50%)';
    popup.style.background = 'linear-gradient(135deg, #198754 0%, #20c997 100%)';
    popup.style.color = 'white';
    popup.style.padding = '20px 30px';
    popup.style.borderRadius = '15px';
    popup.style.boxShadow = '0 10px 30px rgba(0, 0, 0, 0.3)';
    popup.style.zIndex = '9999';
    popup.style.fontWeight = '600';
    popup.style.textAlign = 'center';
    popup.style.minWidth = '300px';
    popup.innerHTML = `<div style='font-size: 18px; margin-bottom: 10px;'>✅</div><div style='font-size: 16px;'>${message}</div><div style='font-size: 12px; margin-top: 10px; opacity: 0.8;'>This popup will close automatically</div>`;
    document.body.appendChild(popup);
    setTimeout(() => {
        popup.style.opacity = '0';
        setTimeout(() => popup.remove(), 500);
    }, duration);
}
