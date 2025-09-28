export default defineNuxtRouteMiddleware(async (to, from) => {
    // You may need to replace this with your actual API or logic to get the user's role
    try {
        const { role } = await useSanctumFetch('/api/user');
        if (role !== 'admin') {
        // Redirect to another page if the user is not an admin
        return navigateTo('/'); // Or any other path (e.g., login page)
        }
    } catch (error) {
        return navigateTo('/login');
    }
  });
  