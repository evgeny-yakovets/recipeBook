import { test, expect } from '@playwright/test';
import { testUser } from '../Fixtures/user';

const uniqueRecipeName = `recipe_name_${Date.now()}`;

async function waitForRecipesToLoad(page) {
  await page.waitForSelector('[data-testid^="recipeCard_"]', { timeout: 30000 });
}

test.describe('Recipes CRUD', () => {
  test.beforeEach(async ({ page }) => {
    await page.goto('/login', { waitUntil: 'networkidle' });

    await page.getByTestId('email').fill(testUser.email);
    await page.getByTestId('password').fill(testUser.password);
    await page.getByTestId('loginButton').click();

    await page.waitForURL('/');
    await waitForRecipesToLoad(page);
  });

  test('user can create recipe', async ({ page, browserName }) => {
    await page.goto('/recipes/create');

    const recipeName = uniqueRecipeName + browserName;

    await page.getByTestId('recipeNameInput').fill(recipeName);
    await page.getByTestId('cuisineTypeInput').fill('Dessert');
    await page.getByTestId('descriptionInput').fill('Delicious cake');
    await page.getByTestId('ingredientsInput').fill('Sugar\nFlour\nEggs');
    await page.getByTestId('stepsInput').fill('Mix ingredients\nBake 30 min');

    await page.getByTestId('saveButton').click();

    await page.waitForURL('/');
    await waitForRecipesToLoad(page);

    await expect(page.getByText(recipeName)).toBeVisible();
  });

});
