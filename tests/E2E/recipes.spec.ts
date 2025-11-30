import { test, expect } from '@playwright/test';
import { testUser } from '../Fixtures/user';

test.describe('Recipes CRUD', () => {

  test.beforeEach(async ({ page }) => {
    await page.goto('/login');

    await page.fill('#email', testUser.email);
    await page.fill('#password', testUser.password);
    await page.click('#loginButton');
    await expect(page).toHaveURL('/');
  });

  test('user can create recipe', async ({ page }) => {
    await page.goto('/recipes/create');

    await page.fill('input[name=name]', 'Test Cake');
    await page.fill('textarea[name=ingredients]', 'Sugar, Flour');
    await page.fill('textarea[name=steps]', 'Mix and bake');

    await page.click('button[type=submit]');

    await expect(page).toHaveURL('/recipes');
    await expect(page.getByText('Test Cake')).toBeVisible();
  });

  test('user can edit recipe', async ({ page }) => {
    await page.goto('/recipes');

    await page.getByRole('link', { name: /edit/i }).first().click();

    await page.fill('input[name=name]', 'Updated Cake');
    await page.click('button[type=submit]');

    await expect(page).toHaveURL('/recipes');
    await expect(page.getByText('Updated Cake')).toBeVisible();
  });

  test('user can delete recipe', async ({ page }) => {
    await page.goto('/recipes');

    await page.getByRole('button', { name: /delete/i }).first().click();

    await expect(page.getByText('Updated Cake')).not.toBeVisible();
  });

});
