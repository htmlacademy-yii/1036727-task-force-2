const autoCompleteJS = new autoComplete({
    // placeHolder: 'Search for Food...',
    data: {
        src: async query => {
            try {
                // Fetch Data from external Source
                const source = await fetch(`http://localhost/api/${query}`);
                // Data is array of `Objects` | `Strings`
                const data = await source.json();

                return data;
            } catch (error) {
                return error;
            }
        },
        // Data 'Object' key to be searched
        keys: ['text']
    },
    resultItem: {
        highlight: true
    },
    events: {
        input: {
            selection: event => {
                const selection = event.detail.selection.value;
                autoCompleteJS.input.value = selection.text;

                document.getElementById('lat').value = selection.pos[1];
                document.getElementById('long').value = selection.pos[0];
            }
        }
    }
});
