// front-end/api-calling/members-api.js

const API_BASE_URL = '/back-end/api/users.php';

async function addMember(memberData) {
    try {
        const response = await fetch(`${API_BASE_URL}?action=create`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(memberData)
        });
        return await response.json();
    } catch (error) {
        console.error('Error adding member:', error);
        return { status: 'error', message: error.message };
    }
}

async function updateMember(memberData) {
    try {
        const response = await fetch(`${API_BASE_URL}?action=update`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(memberData)
        });
        return await response.json();
    } catch (error) {
        console.error('Error updating member:', error);
        return { status: 'error', message: error.message };
    }
}

async function getMember(userId) {
    try {
        const response = await fetch(`${API_BASE_URL}?action=get&user=${userId}`);
        return await response.json();
    } catch (error) {
        console.error('Error getting member:', error);
        return { status: 'error', message: error.message };
    }
}
