Ethereum CLL Preprocessor
------

**Features**

Allows you to enter c-like comments starting with `//`

    ```cpp
    // This is a comment.
    val = tx.data[0]

Strips empty lines.

Allows you to use c++ preprocessor-like defines

    ```cpp
    #define STATIC_ADDRESS 1001
    contract.storage[STATIC_ADDRESS] = 257

Compiles your code using a branch of the Ethereum CLL Compiler: https://github.com/ethereum/compiler

----
**Examples**

Right now, it just outputs the compiled code to stdout, and you copy and paste it. All you have to do is call the bash script using your `.cll` file location as the first parameter.

    ./compile.sh yourfile.cll

Try it with the included cll file.

    ./compile.sh pyramid.cll

----
**Setup**

You just have to init the submodules to 

    [user@host]$ git submodule init


---

Examples:

---

This contains a submodule! http://git-scm.com/book/en/Git-Tools-Submodules

It's the cll compiler @ https://github.com/ethereum/compiler
