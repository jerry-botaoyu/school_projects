using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Microsoft.Xna.Framework;
using Microsoft.Xna.Framework.Graphics;
using Microsoft.Xna.Framework.Input;
using Microsoft.Xna.Framework.Audio;

namespace SpaceInvader
{
    class SpaceShip : PhysicsGameObject
    {
        MouseState lastFrmeMouseState;
        SoundEffect laserSound;

        public SpaceShip(Vector2 position, Texture2D sprite, SoundEffect laserSound)
            : base(position, sprite, new Vector2(0.0f, 0.0f), new Vector2(0.0f, 0.0f))
        {
            lastFrmeMouseState = Mouse.GetState();
            this.laserSound = laserSound;
        }


        public override void Update(GameTime gameTime)
        {
            base.Update(gameTime);

            //-------------------------- Movements ------------------------------
            MouseState mouse = Mouse.GetState();
            Vector2 laserToMouse = new Vector2(position.X - mouse.X, -100);
            position.X = mouse.X;

            rotationAngle = 0.0f;

            if (mouse.LeftButton == ButtonState.Pressed &&
                lastFrmeMouseState.LeftButton != ButtonState.Pressed)
            {
                laserSound.Play();

                const float LASER_SPEED = 300.0f;
                laserToMouse.Normalize();
                laserToMouse *= LASER_SPEED;

                Texture2D laserSprite = Game1.GetInstance().getLaserSprite();
                Projectile laser = new Projectile(position, laserToMouse, laserSprite);
                Game1.GetInstance().AddGameObject(laser);
            }

            lastFrmeMouseState = mouse;

            //------------------ Decrease Lives when collide with enemy projectile ----------
            if (Game1.GetInstance().gameStart)
            {
                decreaseLive();
            }
            


        }

        public void decreaseLive()
        {
            List<GameObject> gameObjects = Game1.GetInstance().getGameObjects();
            for (int i = 0; i < gameObjects.Count; i++)
            {
                GameObject go = gameObjects[i];
                if (go is EnemyProjectile || go is Enemy)
                {
                    PhysicsGameObject pgo = (PhysicsGameObject)go;
                    float distance = (pgo.GetPosition() - position).Length();

                    if (distance < radius + pgo.GetRadius())
                    {
                        gameObjects.Remove(pgo);
                        Game1.GetInstance().numOfLives--;
                        // remove lives
                        for (int j = 0; j < gameObjects.Count; j++)
                        {
                            if (gameObjects[j] is Live)
                            {
                                gameObjects.Remove(gameObjects[j]);
                                break;
                            }
                        }
                    }
                }


            }
        }
    }
}

