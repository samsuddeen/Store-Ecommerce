
// by convention, composable function names start with "use"
export function useErrors() {

    const getErrors =(error, name) => {
        return error ? error[name] : null;
    }

    return { getErrors }; // expose managed state as return value
}
