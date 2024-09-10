document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('routePlannerForm');
    const viaPointsContainer = document.getElementById('viaPointsContainer');
    const addViaPointButton = document.getElementById('addViaPoint');
    const startInput = document.getElementById('start');
    const goalInput = document.getElementById('goal');

  

    addViaPointButton.addEventListener('click', addViaPoint);

    // 既存の経由地点に削除ボタンのイベントリスナーを追加
    document.querySelectorAll('.removeViaPoint').forEach(button => {
        button.addEventListener('click', function() {
            this.closest('div').remove();
            updateViaPointNumbers();
        });
    });

    // オートコンプリートの設定
    setupAutocomplete(startInput);
    setupAutocomplete(goalInput);
    

    function addViaPoint() {
        const viaPointCount = viaPointsContainer.children.length + 1;
        const viaPointDiv = document.createElement('div');
        viaPointDiv.innerHTML = `
            <label for="via${viaPointCount}">経由地点 ${viaPointCount}</label>
            <input type="text" id="via${viaPointCount}" name="via[]" placeholder="例: 新宿駅">
            <button type="button" class="removeViaPoint">削除</button>
        `;
        viaPointsContainer.appendChild(viaPointDiv);
        viaPointDiv.querySelector('.removeViaPoint').addEventListener('click', function() {
            viaPointsContainer.removeChild(viaPointDiv);
            updateViaPointNumbers();
        });
        setupAutocomplete(viaPointDiv.querySelector('input'));
    }

    function updateViaPointNumbers() {
        const viaPoints = viaPointsContainer.querySelectorAll('div');
        viaPoints.forEach((viaPoint, index) => {
            const newNumber = index + 1;
            const label = viaPoint.querySelector('label');
            const input = viaPoint.querySelector('input');
            label.textContent = `経由地点 ${newNumber}`;
            label.setAttribute('for', `via${newNumber}`);
            input.id = `via${newNumber}`;
        });
    }

    function setupAutocomplete(input) {
        let timeout = null;
        input.addEventListener('input', function() {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                const word = this.value;
                if (word.length >= 2) {
                    fetch(`/autocomplete?word=${encodeURIComponent(word)}`)
                        .then(response => {
                            if (!response.ok) {
                                return response.json().then(err => { throw err; });
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.error) {
                                console.error('Error:', data.error);
                                showAutocompleteSuggestions({items: []}, input);
                            } else {
                                showAutocompleteSuggestions(data, input);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showAutocompleteSuggestions({items: []}, input);
                        });
                } else {
                    showAutocompleteSuggestions({items: []}, input);
                }
            }, 300);
        });
    }

    function showAutocompleteSuggestions(data, input) {
        let suggestionList = input.nextElementSibling;
        if (!suggestionList || !suggestionList.classList.contains('autocomplete-suggestions')) {
            suggestionList = document.createElement('ul');
            suggestionList.classList.add('autocomplete-suggestions');
            input.parentNode.insertBefore(suggestionList, input.nextSibling);
        }
        suggestionList.innerHTML = '';
        
        if (!data.items || data.items.length === 0) {
            const li = document.createElement('li');
            li.textContent = '候補がありません';
            li.classList.add('no-suggestions');
            suggestionList.appendChild(li);
            return;
        }
        
        data.items.forEach(item => {
            const li = document.createElement('li');
            li.textContent = item.name;
            if (item.lat && item.lon) {
                li.dataset.lat = item.lat;
                li.dataset.lon = item.lon;
            }
            li.addEventListener('click', function() {
                input.value = item.name;
                input.dataset.lat = this.dataset.lat;
                input.dataset.lon = this.dataset.lon;
                suggestionList.innerHTML = '';
            });
            suggestionList.appendChild(li);
        });
        
        // 候補リストの表示制御
        if (suggestionList.children.length > 0) {
            suggestionList.style.display = 'block';
        } else {
            suggestionList.style.display = 'none';
        }
    }
});