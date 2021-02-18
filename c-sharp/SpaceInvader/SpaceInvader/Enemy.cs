using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Microsoft.Xna.Framework;
using Microsoft.Xna.Framework.Graphics;
using Microsoft.Xna.Framework.Input;

using Microsoft.Xna.Framework.Input;
using Microsoft.Xna.Framework.Audio;
using Microsoft.Xna.Framework.Media;

namespace SpaceInvader
{
    class Enemy : PhysicsGameObject
    {
        SoundEffect explosionSound;
        int hitCounts;
        protected Texture2D flashEnemy;
        protected Texture2D normalEnemy;

        public Enemy(Vector2 position, Texture2D normalEnemy, Texture2D flashEnemy, SoundEffect explosionSound)
            : base(position, normalEnemy, new Vector2(0.0f, 0.0f), new Vector2(0.0f, 0.0f))
        {
            this.explosionSound = explosionSound;
            hitCounts = 0;
            this.normalEnemy = normalEnemy;
            this.flashEnemy = flashEnemy;
        }


        private  int oldSeconds = -1;
        private static bool goRight = true;

        public override void Update(GameTime gameTime)
        {
            base.Update(gameTime);

            //------------------------ Collision Detection -----------------------
            List<GameObject> gameObjects = Game1.GetInstance().getGameObjects();
            for (int i = 0; i < gameObjects.Count; i++)
            {
                GameObject go = gameObjects[i];
                if (go is Projectile)
                {
                    Projectile projectile = (Projectile)go;
                    float distance = (projectile.GetPosition() - position).Length();

                    if (distance < radius + projectile.GetRadius())
                    {
                        explosionSound.Play();
                        Game1.GetInstance().score++;
                        hitCounts++;
                        gameObjects.Remove(projectile);
                        if (hitCounts >= 2)
                        {
                            gameObjects.Remove(this);
                            
                        }
                            
                    }
                }
            }

            if(velocity == new Vector2(0.0f, 0.0f))
            {
                velocity = new Vector2(120f, 0.0f);
            }

            //If out of boundary, change direction
            if (position.X <= 0 || position.X >= Game1.GetInstance().getGameWidth())
            {
                List<GameObject> gameObjects2 = Game1.GetInstance().getGameObjects();
                foreach (GameObject go in gameObjects2)
                {
                    if (go is Enemy)
                    {
                        Enemy enemy = (Enemy)go;
                        enemy.velocity = enemy.velocity * -1;
                        enemy.position.Y = enemy.position.Y + 50;
                    }
                }
            }


            if (hitCounts > 0 && gameTime.TotalGameTime.Milliseconds % 100 == 0)
            {
                if (this.sprite == this.normalEnemy)
                    this.sprite = this.flashEnemy;
                else
                    this.sprite = this.normalEnemy;
            }

        }

        public void shoot()
        {
            Vector2 laserToMouse = new Vector2(0, position.Y);
            //Calculate Velocity for laser
            const float LASER_SPEED = 300.0f;
            laserToMouse.Normalize();
            laserToMouse *= LASER_SPEED;

            //Instantiate laser
            Texture2D laserSprite = Game1.GetInstance().getLaserSprite();
            EnemyProjectile enemyProjectile = new EnemyProjectile(position, laserToMouse, Game1.GetInstance().enemyProjectileSprite);
            Game1.GetInstance().AddGameObject(enemyProjectile);

        }




    }
}
