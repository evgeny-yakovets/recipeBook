import { test, expect } from '@playwright/test';
import { testUser } from '../Fixtures/user';

test.describe('User Authentication', () => {

  test('user can register', async ({ page }) => {
    await page.goto('/register', { waitUntil: 'networkidle'});

    await page.fill('#email', testUser.email);

    await page.fill('#name', testUser.name);
    await page.fill('#password', testUser.password);
    await page.fill('#password_confirmation', testUser.password);

    await page.click('#register'); 

    await expect(page).toHaveURL('/');
    await expect(page.getByText(testUser.name)).toBeVisible();
  });

  test('user can login', async ({ page }) => {
    await page.goto('/login', { waitUntil: 'networkidle' });

    await page.fill('#email', testUser.email);
    await page.fill('#password', testUser.password);
    await page.click('#loginButton');


    await expect(page).toHaveURL('/');
    await expect(page.getByText(testUser.name)).toBeVisible();
  });

});
