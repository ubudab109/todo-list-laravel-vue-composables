import method from '../axios/axios-setup';

export default function useFiles() {
    /**
     * The function `uploadFiles` is an asynchronous function that takes a file as a parameter and
     * uploads it to an API endpoint using a POST request.
     * @param {File} file - The `file` parameter is the file that you want to upload. It should be a valid
     * file object.
     */
    const uploadFiles = async (files) => {
        let formBody = new FormData();
        for (var i = 0; i < files.length; i++) {
            formBody.append(`files[${i}]`, files[i]);
        }
        try {
            const response = await method.post('/api/files', formBody, {
                headers: {
                    "Content-Type": "multipart/form-data",
                }
            });
            const data = response.data.response;
            return data;
        } catch (err) {
            return err.response.data.message;
        }
    }

    /**
     * The function `deleteFiles` is an asynchronous function that deletes a file using the provided
     * file ID and file name, and handles any errors that occur during the deletion process.
     * @param {Number} fileId - The fileId parameter is the unique identifier of the file that you want to
     * delete.
     * @param {String} fileName - The `fileName` parameter is the name of the file that you want to delete.
     */
    const deleteFiles = async (fileId, fileName) => {
        try {
            await method.delete(`/api/files/${fileId}`, {
                data: {filename: fileName}
            });
        } catch (err) {
            return err.response.data.message;
        }
    };

    return {
        uploadFiles,
        deleteFiles,
    };
}