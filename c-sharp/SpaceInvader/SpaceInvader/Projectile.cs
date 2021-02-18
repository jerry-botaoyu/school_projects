using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Microsoft.Xna.Framework;
using Microsoft.Xna.Framework.Graphics;
using Microsoft.Xna.Framework.Input;

namespace SpaceInvader
{
    class Projectile : PhysicsGameObject
    {


        public Projectile(Vector2 position, Vector2 velocity, Texture2D sprite)
            : base(position, sprite, velocity, new Vector2(0.0f, 0.0f))
        {
            MouseState mouseState = Mouse.GetState();

        }
    }
}
