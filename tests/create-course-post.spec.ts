import { test, expect } from '@wordpress/e2e-test-utils-playwright';

const testTitle = 'Test Course';
const testContent = 'This is a test Course.';

test.describe('Create Course Post', () => {
	test('Ensure course post type is properly registered', async ({
		requestUtils,
	}) => {
		const coursePosts = await requestUtils.rest({
			path: '/wp/v2/course',
			method: 'GET',
		});
		expect(coursePosts).toBeDefined();
	});

	test('Course post created', async ({ admin, editor, requestUtils }) => {
		await admin.createNewPost({
			title: testTitle,
			content: testContent,
			postType: 'course',
		});
		// Publish the course
		await editor.publishPost();

		// Get the created course via REST API
		const coursePosts = await requestUtils.rest({
			path: '/wp/v2/course',
			method: 'GET',
		});
		// Get the first item out of the coursePosts array
		const coursePost = coursePosts?.[0];
		// Verify the course was created with correct title and content
		expect(coursePost.title.rendered).toBe(testTitle);
		expect(coursePost.content.rendered).toContain(testContent);
	});
});
