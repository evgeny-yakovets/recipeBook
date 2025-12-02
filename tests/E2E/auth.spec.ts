import { test, expect } from '@playwright/test';
import { testUser } from '../Fixtures/user';

test.describe('User Authentication', () => {

  test('user can register', async ({ page }) => {
    await page.goto('/register', { waitUntil: 'networkidle'});

    const uniqueEmail = `test_${Date.now()}@example.com`;

    await page.getByTestId('name').fill(testUser.name);
    await page.getByTestId('email').fill(uniqueEmail);
    await page.getByTestId('password').fill(testUser.password);
    await page.getByTestId('password_confirmation').fill(testUser.password);

    await page.getByTestId('register').click(); 

    await expect(page).toHaveURL('/', { timeout: 20000 });
    await expect(page.getByTestId('userNameDropdown')).toBeVisible();
    await expect(page.getByTestId('userNameDropdown')).toHaveText(testUser.name);
  });

  test('user can login', async ({ page }) => {
    await page.goto('/login', { waitUntil: 'networkidle' });

    await page.getByTestId('email').fill(testUser.email);
    await page.getByTestId('password').fill(testUser.password);
    await page.getByTestId('loginButton').click(); 

    await expect(page).toHaveURL('/', { timeout: 20000 });
    await expect(page.getByTestId('userNameDropdown')).toBeVisible();
    await expect(page.getByTestId('userNameDropdown')).toHaveText(testUser.name);
  });

});
