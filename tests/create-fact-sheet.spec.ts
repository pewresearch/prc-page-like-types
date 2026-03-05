import { test, expect } from '@wordpress/e2e-test-utils-playwright';

const testTitle = 'Test Fact Sheet';
const testContent = 'This is a test Fact Sheet.';

test.describe('Create Fact Sheet Post', () => {
	test('Ensure fact sheet post type is properly registered', async ({
		requestUtils,
	}) => {
		const factSheetPosts = await requestUtils.rest({
			path: '/wp/v2/fact-sheet',
			method: 'GET',
		});
		expect(factSheetPosts).toBeDefined();
	});

	test('Fact sheet post created', async ({ admin, editor, requestUtils }) => {
		await admin.createNewPost({
			title: testTitle,
			content: testContent,
			postType: 'fact-sheet',
		});
		// Publish the fact sheet
		await editor.publishPost();

		// Get the created fact sheet via REST API
		const factSheetPosts = await requestUtils.rest({
			path: '/wp/v2/fact-sheet',
			method: 'GET',
		});
		// Get the first item out of the factSheetPosts array
		const factSheetPost = factSheetPosts?.[0];
		// Verify the fact sheet was created with correct title and content
		expect(factSheetPost.title.rendered).toBe(testTitle);
		expect(factSheetPost.content.rendered).toContain(testContent);
	});
});
