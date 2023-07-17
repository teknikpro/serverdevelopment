const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))


// share to twitter
function shareOnTwitter() {
    const url = encodeURIComponent(window.location.href);
    const tweetText = encodeURIComponent(`ada promo niih di dfunstation, silahkan cek link dibawah ini`);
    const twitterUrl = `https://twitter.com/intent/tweet?url=${url}&text=${tweetText}`;
    window.open(twitterUrl, '_blank', 'width=600,height=400');
    location.reload();
  }

// share to facebook
function shareOnFacebook() {
    const url = encodeURIComponent(window.location.href);
    const facebookUrl = `https://www.facebook.com/sharer/sharer.php?u=${url}`;
    window.open(facebookUrl, '_blank', 'width=600,height=400');
    location.reload();
  }

// copy link
function copyUrlToClipboard() {
    const url = window.location.href;
    navigator.clipboard.writeText(url)
      .then(() => {
        alert('Link berhasil disalin!');
        location.reload();
      })
      .catch((error) => {
        console.error('Gagal menyalin link: ', error);
      });
  }