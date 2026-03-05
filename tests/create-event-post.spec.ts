import { test, expect } from '@wordpress/e2e-test-utils-playwright';

const testTitle = 'Test Event';
const testContent = 'This is a test Event.';

test.describe('Create Event Post', () => {
	test('Ensure event post type is properly registered', async ({
		requestUtils,
	}) => {
		const eventPosts = await requestUtils.rest({
			path: '/wp/v2/events',
			method: 'GET',
		});
		expect(eventPosts).toBeDefined();
	});

	test('Event post created', async ({ admin, editor, requestUtils }) => {
		await admin.createNewPost({
			title: testTitle,
			content: testContent,
			postType: 'events',
		});
		// Publish the event
		await editor.publishPost();

		// Get the created event via REST API
		const eventPosts = await requestUtils.rest({
			path: '/wp/v2/events',
			method: 'GET',
		});
		// Get the first item out of the eventPosts array
		const eventPost = eventPosts?.[0];
		// Verify the event was created with correct title and content
		expect(eventPost.title.rendered).toBe(testTitle);
		expect(eventPost.content.rendered).toContain(testContent);
	});
});
